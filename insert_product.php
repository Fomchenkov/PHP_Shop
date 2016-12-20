<?php

/*
	Проверить с помощью JavaScript,
	что бы все поля были заполнены
*/

// Если форма была отправлена
if (isset($_POST['sbm']))
{
	require_once 'config.php';
	// Соединение с DataBase
	$mySQL = new mysqli($host, $dbuser, $dbpass, $dbname);
	$mySQL->set_charset("utf8");

	// Объявление переданных переменных
	$name = trim(htmlspecialchars($_POST['name']));
	$price = trim((int) $_POST['price']);
	$currency = $_POST['currency'];
	$owner = trim(htmlspecialchars($_POST['owner']));
	$description = trim(htmlspecialchars($_POST['description']));

	$imageSrc = null;

	if (is_uploaded_file($_FILES['image']['tmp_name']))
	{
		// if ($_FILES['image']['type'] != 'img/jpg')

		// Исходный файл
		$file = $_FILES['image'];

		// Выборка последнего id продукта
		$sqlQuery = "SELECT MAX(product_id) FROM `products`";
		// $row = $mySQL->query($sqlQuery);
		$pr_id = $mySQL->query($sqlQuery)->fetch_row()[0];
		// К последнему product_id прибавляем 1 
		$newDir = $pr_id + 1 . '/';

		// Ссылка на фото
		$pathname = 'products/img/' . $newDir;
		// Создание новой папки для продукта
		mkdir($pathname);
		// Перемешаем изображение продукта в новую папку
		if (move_uploaded_file($file['tmp_name'], $pathname . $file['name']))
		{
			echo 'Файл успешно перемещен<br>';
			$imageSrc = $pathname . $file['name'];
		}
		else
		{
			echo 'Не удается переместить файл<br>';
			echo '<p><a href="index.php">На главную</a></p>';
			exit();
		}
	}

	// Запрос на занесение нового товара в DataBase
	$sqlQuery = "
	INSERT INTO `products`
	(product_name, product_price, product_currency, product_owner, product_description, product_images_src)
	VALUES 
	('$name', '$price', '$currency', '$owner', '$description', '$imageSrc')";
	// При успешном выполнении
	if ($mySQL->query($sqlQuery))
	{
		// id только что добавленного продукта
		$sqlQuery = "SELECT MAX(product_id) FROM `products`";
		$product_id = $mySQL->query($sqlQuery)->fetch_row()[0] + 1;

		echo 'Ваш товар успешно занесен в базу данных<br>';
		echo '<a href="index.php">На главную</a> ';
		echo "<a href=\"products/index.php?view=$product_id\">Посмотреть товар</a>";
	}
	else
	{
		echo $mySQL->error;
		echo 'Ошибка при занесении товара в базу данных<br>';
		exit();
	}
}
else
{
	echo 
	'<form enctype="multipart/form-data" method="POST" action="">
	Именование товара<br>
		<input type="input" name="name" autofocus><br>
	Цена товара<br>
		<input type="input" name="price"><br>
	Валюта<br>
		<select name="currency">
			<option>Доллар</option>
			<option selected>Рубль</option>
			<option>Евро</option>
		</select><br>
	Ваше Имя<br>
		<input type="input" name="owner"><br>
	Описание товара<br>
		<textarea name="description" maxlength="300"></textarea><br>
	Фото товара<br>
		<input type="file" name="image" accept="image/jpeg,image/png"><br><br>
		<input type="submit" name="sbm" value="Разместить товар в магазине">
	</form>';
}

?>