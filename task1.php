<!DOCTYPE html>
<html>
	<head>
		<title>Test_1</title>
		<meta charset="utf-8">
	</head>
	<body>
		<h1>Введите размер квадрата:</h1>
		<form action = "<?=$_SERVER['PHP_SELF']?>" method = "POST">
			<input type = "number" name = "num"/>
			<p><input type = "submit" value = "Отправить"></p>
		</form>
		<p>Магический квадрат:</p>
		<p>
			<table>
				<?php 
					if (isset($_POST["num"]))
					{
						buildMagicSquare($_POST["num"]);
					} 
				?>
			</table>
		</p>
	</body>
</html>

<?php
	function buildMagicSquare($kol)
	{
		if ($kol == 2 || $kol <= 0) // возможно построить магический квадрат только положительного размера и не равный 2-м
		{
			echo "Невозможно построить магический квадрат!";
		}
		else // построить магический квадрат возможно
		{
			$n = $kol * 2 - 1;
			$arr = array(array());
			if ($kol % 2 == 1) // нечетный магический квадрат
			{
				for ($i = 0; $i < $n; $i++)
				{
					for ($j = 0; $j < $n; $j++)
					{
						$arr[$i][$j] = 0;
					}
				}
				$arr = tmpArrForOdd($arr, $n);
				$arr = oddArr($arr, $kol, $n);
				for ($i = 0; $i < $n; $i++) // вывод магического квадрата в табличном виде
				{
					$fl = false;
					for ($j = 0; $j < $n; $j++)
					{
						if ($arr[$i][$j] != 0)
						{
							if ($fl == false)
							{
								echo "<tr>";
								$fl = true;
							}
							echo "<th>".$arr[$i][$j]."</th>";
						}
					}
					if ($fl)
					{
						echo "</tr>";
					}
				}
			}
			else // четный магический квадрат
			{
				$temp = 1;
				for ($i = 0; $i < $kol; $i++)
				{
					for ($j = 0; $j < $kol; $j++)
					{
						$arr[$i][$j] = $temp;
						$temp++;
					}
				}
				if (($kol / 2) % 2 == 0) // четно-четный квадрат
				{
					$arr = evenEvenArr($arr, $kol);
				}
				else
				{
					$arr = evenOddArr($arr, $kol); // четно-нечетный квадрат
				}
				for ($i = 0; $i < $kol; $i++) // вывод магического квадрата в табличном виде
				{
					echo "<tr>";
					for ($j = 0; $j < $kol; $j++)
					{
						if ($arr[$i][$j] != 0)
						{
							echo "<th>".$arr[$i][$j]."</th>";
						}
					}
					echo "</tr>";
				}
			}
		}
	}
	function tmpArrForOdd($arr, $n) // вспомогательный массив для квадрата нечетного размера
	{
		$coun = 1;
		$x = ($n + 1) / 2 - 1;
		$y = 0;
		for ($i = 0; $i < $n / 2; $i++)
		{
			$tmp1 = $x;
			$tmp2 = $y;
			for ($j = 0; $j < ($n + 1) / 2; $j++)
			{
				$arr[$tmp1][$tmp2] = $coun;
				$tmp1--;
				$tmp2++;
				$coun++;
			}
			$x++;
			$y++;
		}
		return $arr;
	}
	function oddArr($arr, $kol, $n) // расчет квадрата нечетного размера
	{
		for ($i = 0; $i < $n; $i++)
		{
			for ($j = 0; $j < $n; $j++)
			{
				if ($arr[$i][$j] != 0 && ($i < ($kol - 1) / 2 || $i >= $kol + ($kol - 1) / 2 || $j < ($kol - 1) / 2 || $j >= $kol + ($kol - 1) / 2))
				{
					if ($i < ($kol - 1) / 2)
					{
						$arr[$i + $kol][$j] = $arr[$i][$j];
						$arr[$i][$j] = 0;
					}
					else if ($i >= $kol + ($kol - 1) / 2)
					{
						$arr[$i - $kol][$j] = $arr[$i][$j];
						$arr[$i][$j] = 0;
					}
					else if ($j < ($kol - 1) / 2)
					{
						$arr[$i][$j + $kol] = $arr[$i][$j];
						$arr[$i][$j] = 0;
					}
					else if ($j >= $kol + ($kol - 1) / 2)
					{
						$arr[$i][$j - $kol] = $arr[$i][$j];
						$arr[$i][$j] = 0;
					}
				}
			}
		}
		return $arr;
	}
	function evenEvenArr($arr, $kol) // расчет квадрата четно-четного размера (4, 8, 12...)
	{
		for ($i = 0; $i < $kol / 2; $i ++)
		{
			for ($j = 0; $j < $kol / 2; $j ++)
			{
				if (($i + $j) % 2 == 0)
				{
					$tmp = $arr[$i][$j];
					$arr[$i][$j] = $arr[$kol - 1 - $i][$kol - 1 - $j];
					$arr[$kol - 1 - $i][$kol - 1 - $j] = $tmp;
				}
			}
		}
		for ($i = 0; $i < $kol / 2; $i ++)
		{
			for ($j = $kol / 2; $j < $kol; $j ++)
			{
				if (($i + $j) % 2 == 1)
				{
					$tmp = $arr[$i][$j];
					$arr[$i][$j] = $arr[$kol - 1 - $i][$kol - 1 - $j];
					$arr[$kol - 1 - $i][$kol - 1 - $j] = $tmp;
				}
			}
		}
		return $arr;
	}
	function evenOddArr($arr, $kol) // расчет квадрата четно-нечетного размера (6, 10, 14...)
	{
		$tmparr = array(array());
		$tmparr2 = array(array());
		for ($i = 0; $i < $kol - 1; $i++)
		{
			for ($j = 0; $j < $kol - 1; $j++)
			{
				$tmparr[$i][$j] = 0;
			}
		}
		$tmparr = tmpArrForOdd($tmparr, $kol - 1);
		$tmparr = oddArr($tmparr, $kol / 2, $kol - 1);
		$a = 0;
		$b = 0;
		for ($i = 0; $i < $kol - 1; $i++)
		{
			for ($j = 0; $j < $kol - 1; $j++)
			{
				if ($tmparr[$i][$j] != 0)
				{
					$tmparr2[$a][$b] = $tmparr[$i][$j];
					if ($b == $kol / 2 - 1)
					{
						$b = 0;
						$a++;
					}
					else
					{
						$b++;
					}
				}
			}
		}
		for ($i = 0; $i < $kol / 2; $i++)
		{
			for ($j = 0; $j < $kol / 2; $j++)
			{
				$arr[$i][$j] = $tmparr2[$i][$j];
			}
		}
		for ($i = $kol / 2; $i < $kol; $i++)
		{
			for ($j = $kol / 2; $j < $kol; $j++)
			{
				$arr[$i][$j] = $tmparr2[$i - $kol / 2][$j - $kol / 2] + (($kol / 2) * ($kol / 2));
			}
		}
		for ($i = 0; $i < $kol / 2; $i++)
		{
			for ($j = $kol / 2; $j < $kol; $j++)
			{
				$arr[$i][$j] = $tmparr2[$i][$j - $kol / 2] + (($kol / 2) * ($kol / 2) * 2);
			}
		}
		for ($i = $kol / 2; $i < $kol; $i++)
		{
			for ($j = 0; $j < $kol / 2; $j++)
			{
				$arr[$i][$j] = $tmparr2[$i - $kol / 2][$j] + (($kol / 2) * ($kol / 2) * 3);
			}
		}
		
		$tmp = $arr[0][0];
		$arr[0][0] = $arr[$kol / 2][0];
		$arr[$kol / 2][0] = $tmp;
		
		$tmp = $arr[$kol / 2 - 1][0];
		$arr[$kol / 2 - 1][0] = $arr[$kol - 1][0];
		$arr[$kol - 1][0] = $tmp;
		
		for ($i = 1; $i < $kol / 2 - 1; $i++)
		{
			$tmp = $arr[$i][1];
			$arr[$i][1] = $arr[$i + $kol / 2][1];
			$arr[$i + $kol / 2][1] = $tmp;
		}
		
		if ($kol != 6)
		{
			for ($j = ($kol - 2) / 4 + 2; $j < ($kol - 2) / 4 + 2 + $kol / 2 - 3; $j++)
			{
				for ($i = 0; $i < $kol / 2; $i++)
				{
					$tmp = $arr[$i][$j];
					$arr[$i][$j] = $arr[$i + $kol / 2][$j];
					$arr[$i + $kol / 2][$j] = $tmp;
				}
			}
		}
		return $arr;
	}
?>