<?php
require_once ('helpers.php');
$con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
$sql_category = "SELECT title, cod FROM category";
$result_category = mysqli_query($con, $sql_category);
$categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
$is_auth = rand(0, 1);
$user_name = 'Konstantin'; // укажите здесь ваше имя
$sql_ads = "SELECT lot.id as id, name, title as category, price_rate as price, image as url, date_completion as calculation_date FROM category
    INNER JOIN lot ON category.id = lot.id_category
    INNER JOIN rate ON lot.id = rate.id_lot
    WHERE lot.date_creation < lot.date_completion
    ORDER BY date_creation ASC";
$result_ads = mysqli_query($con, $sql_ads);
$ads = mysqli_fetch_all($result_ads, MYSQLI_ASSOC);

$main_content = include_template('main.php', ['ads' => $ads, 'categories' => $categories]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories]);
print($layout_content);
