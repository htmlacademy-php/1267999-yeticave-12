<?php
require_once ('helpers.php');
require_once ('bd.php');

if (!$con) {
    $content = include_template('404.php');
}
else {
    $lot_id = filter_input(INPUT_GET, 'id');
    if (!$lot_id) {
        $content = include_template('404.php');
    } else {
        $lots = get_lots();
        $lot_in_lots = in_array($lot_id, array_column($lots, 'id'));
        if (!$lot_in_lots) {
            $content = include_template('404.php');
        } else {
            $ads_lot = get_ads_lot($lot_id);
            $ads_lot['lot_timer'] = get_date($ads_lot['calculation_date'])['is_finishing'];
            $ads_lot['calculation_date'] = get_date($ads_lot['calculation_date'])['times'];
            $content = include_template('model_lot.php', ['ads_lot' => $ads_lot]);
        }
    }
}

print($content);

