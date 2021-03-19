<?php
require_once('init.php');
if (!$con) {
    $content = include_template('404.php');
}
else {
    $lot_id = filter_input(INPUT_GET, 'id');
    if (!$lot_id) {
        $content = include_template('404.php');
    } else {
        $lots = get_lots($con);
        $lot_in_lots = in_array($lot_id, array_column($lots, 'id'));
        if (!$lot_in_lots) {
            $content = include_template('404.php');
        } else {
            $ads_lot = get_ads_lot($lot_id, $con);
            $ads_lot['lot_timer'] = get_date($ads_lot['calculation_date'])['is_finishing'];
            $ads_lot['calculation_date'] = get_date($ads_lot['calculation_date'])['times'];
            $main_content = include_template('model_lot_template.php', ['ads_lot' => $ads_lot]);
            $content = include_template('other_layout.php', ['content' => $main_content, 'categories' => $categories, 'user' => $_SESSION, 'title' => 'Добавление лота']);
        }
    }
}
print($content);

