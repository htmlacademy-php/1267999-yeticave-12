<?php
require_once ('helpers.php');
require_once ('bd.php');

$categories = get_categories();
$is_auth = rand(0, 1);
$user_name = 'Konstantin';
$ads = get_ads();

$main_content = include_template('main.php', ['ads' => $ads, 'categories' => $categories]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories]);
print($layout_content);
