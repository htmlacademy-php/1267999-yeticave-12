<?php
require_once ('helpers.php');
require_once ('bd.php');

$categories = get_categories();
$is_auth = rand(0, 1);
$user_name = 'Konstantin';
$ads = get_ads();
foreach ($ads as $key=>$value) {
    $date_completion = get_date($value['calculation_date'])['times'];
    $lot_timer = get_date($value['calculation_date'])['is_finishing'];
    $ads[$key]['calculation_date'] = $date_completion;
    $ads[$key]['lot_timer'] = $lot_timer;
}
$main_content = include_template('main.php', ['ads' => $ads, 'categories' => $categories]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories]);
print($layout_content);
