<?php
require_once('init.php');
$ads = get_ads($con);
foreach ($ads as $key => $ad) {
    $date_completion = get_date($ad['calculation_date'])['times'];
    $lot_timer = get_date($ad['calculation_date'])['is_finishing'];
    $ads[$key]['calculation_date'] = $date_completion;
    $ads[$key]['lot_timer'] = $lot_timer;
}
$main_content = include_template('index_template.php', ['ads' => $ads, 'categories' => $categories]);
$layout_content = include_template('index_layout.php',
    ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories, 'user' => $user]);
print($layout_content);
