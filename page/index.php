<?php

if (isset($_GET['page']))
{
	$page = $_GET['page'];

	// Проверка на корректность view
	if ($page <= 1) 
	{
		header("Location: ../index.php");
	}

	echo 'Page #' . $page;

	// Подключение файла конфигурации
	require_once '../config.php';
	// Соединяемся с DataBase
	$mySQL = new mysqli($host, $dbuser, $dbpass, $dbname);
	// Настройка кодировки
	$mySQL->query("SET NAMES UTF8");
	// Количество выводимых записей
	$writesCount = 10;

	/*
		Вывод записей:
		page 1 с 1 по 10;
		page 2 с 11 по 20;
		page 3 c 21 по 30
		...
		(product_id как length - на 1 больше индекса)
	*/
	// С какой записи начинать выборку
	$startWroteIndex = $page * $writesCount - $writesCount;

	// Запрос на вывод списка товаров из базы данных
	$sqlQuery = "SELECT * FROM `products` ORDER BY product_id DESC LIMIT $startWroteIndex, $writesCount";

	$result = $mySQL->query($sqlQuery);
/*
	// Парсинг товаров
	while ($row = $result->fetch_row())
	{
		// Проверка на существование фото товара
		if ($row[6] == null)
		{
			$image = 'products/img/index.jpg';
		} 
		else 
		{
			$image = $row[6];
		}

		// Вывод товаров
		echo 
		"<div class=\"product\">
		<a href=\"../index.php?view=$row[0]\">
			Product name: $row[1] <br>
			Price: $row[2] <br>
			Currency: $row[3] <br>
			Product Owner: $row[4] <br>
			Description: $row[5] <br>
			<img src=\"../$image\" width=\"300\" height=\"200\">
		</a>
		</div>";
		echo '<br>';
	}
*/

	// Парсинг товаров
	while ($row = $result->fetch_row())
	{
		// Проверка на существование фото товара
		if ($row[6] == null) $row[6] = 'products/img/index.jpg';

		// Вывод товаров
		echo 
		"<div class=\"product\">
		<p>
		<a href=\"../index.php?view=$row[0]\">
			Product name: $row[1] <br>
			Price: $row[2] <br>
			Currency: $row[3] <br>
			Product Owner: $row[4] <br>
			Description: $row[5] <br>
			<img src=\"../$row[6]\" width=\"300\" height=\"200\">
		</a>
		</p>
		</div>";
	}

	// Подсчет общего количества записей в db
	$totalRows = $mySQL->query("SELECT * FROM `products`")->num_rows;
	// Общее количество страниц
	$page_count = ceil($totalRows / $writesCount);

	echo 'Pages: ';
	// Парсинг ссылок на все страницы
	for ($i = 1, $max = $page_count; $i <= $max; $i++)
	{
		echo "<a href=\"index.php?page=$i\">$i</a> ";
	}
}
else
{
	header("Location: ../index.php");
}

?>