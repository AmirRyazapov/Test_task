<!DOCTYPE html>
<html>
	<head>
		<title>Test_2</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>Введите полный URL веб-сайта:</h1>
		<form action = "<?=$_SERVER['PHP_SELF']?>" method = "POST">
			<textarea cols = "80" rows = "3" type = "text" name = "url"></textarea>
			<p><input type = "submit" value = "Отправить"></p>
		</form>
		<p>
			<?php 
				if (isset($_POST["url"]))
				{
					parse($_POST["url"]);
				} 
			?>
		</p>
	</body>
</html>

<?php
	function parse($url)
	{
		if(@get_headers($url, 1))
		{
			$head = array // для обращения к некоторым сайтам необходимо имитировать вызов "от браузера"
			(
				'http'=>array
				(
					'method'=>"GET",
					'header'=> "User-Agent: Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36\r\n"
				)
			);

			$context = stream_context_create($head);
			$xml = file_get_contents($url, false, $context); // получаем содержимое сайта и записываем его в строку
			
			// убираем заголовок, содержимое css и js
			$xml = preg_replace('#<script[^>]*>.*?</script>#is', '', $xml);
			$xml = preg_replace('#<title[^>]*>.*?</title>#is', '', $xml);
			$xml = preg_replace('#<style[^>]*>.*?</style>#is', '', $xml);
			
			$xml = mb_strtolower($xml); // строка в нижний регистр, для корректного подсчета слов на сайте
			$dom = new DOMDocument('1.0', 'utf-8'); // инициализация объекта класса DOMDocument
			libxml_use_internal_errors(true);
			$dom->loadHTML($xml, LIBXML_PARSEHUGE);
			$xml1 = simplexml_load_string($dom->saveXML($dom)); // строка в объект
			libxml_use_internal_errors(false);
			if ($xml1 != false)
			{
				$str = removeTags($xml1, ''); 
				$arr = str_word_count($str, 1, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя"); // получаем массив слов на странице
				
				$arr = array_count_values($arr); // считаем повторения слов
				array_multisort($arr, SORT_DESC ); // сортируем массив по убыванию
				$arr1 = array_keys($arr);
				$arr2 = array_values($arr);
				// вывод массива
				for ($i = 0; $i < count($arr); $i++)
				{
					if ($i < 10)
					{
						echo '<p>'.'<b>'.($i + 1).'. '.mb_convert_encoding($arr1[$i], 'UTF-8').' - '.$arr2[$i].'</b>'.'</p>';
					}
					else
					{
						echo '<p>'.($i + 1).'. '.mb_convert_encoding($arr1[$i], 'UTF-8').' - '.$arr2[$i].'</p>';
					}
				}
			}
			else
			{
				echo "Не удалось получить данные со страницы";
			}
		}
		else
		{
			echo "Ошибка! Проверьте правильность введенной ссылки!";
		}
	}
	
	function removeTags(SimpleXMLElement $xml, $str) 
	{
		foreach ($xml->children() as $node) 
		{
			if ($node->count() != 0)
			{
				$str = removeTags($node, $str);
			}
			else
			{
				$str = $str.strip_tags(trim($node)).' ';
			}
		}
		return $str;
	}
?>