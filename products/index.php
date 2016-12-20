<?php

if (isset($_GET['view']))
{
	$view = $_GET['view'];

	// Проверка на корректность view
	if ($view <= 0) 
	{
		header('Location: ../index.php');
	}

	require_once '../config.php';
	// Соединяемся с DataBase
	$mySQL = new mysqli($host, $dbuser, $dbpass, $dbname);
	// Настройка кодировки
	$mySQL->query("SET NAMES UTF8");
	// Запрос на выборку данных о товаре из DataBase
	$sqlQuery = "SELECT * FROM `products` WHERE product_id = $view";
	$result = $mySQL->query($sqlQuery);

	// Если в DataBase товар не найден
	if (!$result)
	{
		fail404();
	}

	// Вывод информации о товаре
	$row = $result->fetch_row();
	
	// Если product_id не найден
	if ($row[0] == null) 
	{
		fail404();
	}
	// Если фото к товару не найдено ставим стандартное фото
	if ($row[6] == null) $row[6] = 'products/img/index.jpg';

	echo 
	"<div>
		<p>
			Уникальный идентификатор товара: $row[0]
		</p>
		<p>
			Именование товара: $row[1]
		</p>
		<p>
			Цена товара: $row[2] $row[3]
		</p>
		<p>
			Владелец товара: $row[4]
		</p>
		<p>
			Описание к товару: $row[5]
		</p>
		<p>
			Фотограция товара: <img width=\"300\" heigth=\"200\" src=\"../$row[6]\">
		</p>
	</div>";

	echo '<div><p><a href="../index.php">На главную</a></p></div>';
}
else
{
	header('Location: ../index.php');
}


function fail404()
{
	echo 'Товар удален или не существует';
	echo '<p><a href="../index.php">На главную</a></p>';
	exit();
}

?>