<?php

// Установка кодировки
// mb_internal_encoding("UTF-8");

// При существовании view перекидываем на страницу товара
if (isset($_GET['view']))
{
	$view = $_GET['view'];
	header("Location: products/index.php?view=$view");
}

// Подключение файла конфигурации
require_once 'config.php';
// Соединяемся с DataBase
$mySQL = new mysqli($host, $dbuser, $dbpass, $dbname);
// Настройка кодировки
$mySQL->query("SET NAMES UTF8");
// Количество записей, выводимых на одной страницы
$writesCount = 10;
// Запрос на вывод списка товаров из базы данных
$sqlQuery = "SELECT * FROM `products` ORDER BY product_id DESC LIMIT 0, $writesCount";

$result = $mySQL->query($sqlQuery);
// Парсинг товаров
while ($row = $result->fetch_row())
{
	// Проверка на существование фото товара
	if ($row[6] == null) $row[6] = 'products/img/index.jpg';

	// Вывод товаров
	echo 
	"<div class=\"product\">
	<p>
	<a href=\"index.php?view=$row[0]\">
		Product name: $row[1] <br>
		Price: $row[2] <br>
		Currency: $row[3] <br>
		Product Owner: $row[4] <br>
		Description: $row[5] <br>
		<img src=\"$row[6]\" width=\"300\" height=\"200\">
	</a>
	</p>
	</div>";
}

// Подсчет общего количества записей в db
$totalRows = $mySQL->query("SELECT * FROM `products`")->num_rows;
// Общее количество страниц
$page_count = ceil($totalRows / $writesCount);

echo '<div id="pages">Pages: ';
// Парсинг ссылок на все страницы
for ($i = 1, $max = $page_count; $i <= $max; $i++)
{
	echo "<a href=\"page/index.php?page=$i\">$i</a>";
	echo ' ';
}
echo '</div>';

?>