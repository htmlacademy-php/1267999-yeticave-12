<?php
require_once ('helpers.php');
$con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
if (!$con) {
    $content = include_template('404.php');
}
else {
    $lot_id = filter_input(INPUT_GET, 'id');
    if (!$lot_id) {
        $content = include_template('404.php');
    } else {
        $lot_sql = "SELECT lot.id FROM lot";
        $result_lot = mysqli_query($con, $lot_sql);
        $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
        $lot_in_lots = in_array($lot_id, array_column($lots, 'id'));
        if (!$lot_in_lots) {
            $content = include_template('404.php');
        } else {
            $sql_ads = "SELECT lot.id as id, title as category, name, image as url, description, date_completion as calculation_date FROM lot
        INNER JOIN category ON lot.id_category = category.id
        WHERE lot.id = $lot_id";
            $result_ads = mysqli_query($con, $sql_ads);
            $ads = mysqli_fetch_all($result_ads, MYSQLI_ASSOC)[0];
            $content = include_template('model_lot.php', ['ads' => $ads]);
        }
    }
}
print($content);

