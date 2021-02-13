<?php
require_once ('helpers.php');
$con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
if (!$con) {
    $content = include_template('404.php');
}
else {
    $lot = filter_input(INPUT_GET, 'id');;
    if (!$lot) {
        $content = include_template('404.php');
    }
    else {
        $sql_ads = "SELECT lot.id as id, name, title as category, price_rate as price, image as url, date_completion as calculation_date FROM category
        INNER JOIN lot ON category.id = lot.id_category
        INNER JOIN rate ON lot.id = rate.id_lot
        WHERE lot.id = $lot";
        $result_ads = mysqli_query($con, $sql_ads);
        $ads = mysqli_fetch_all($result_ads, MYSQLI_ASSOC);
        $content = include_template('model_lot.php', ['ads' => $ads]);
    }
}


//
print($content);

