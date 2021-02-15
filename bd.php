<?php

$con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");

function get_categories() {
    $con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
    $sql_category = "SELECT title, cod FROM category";
    $result_category = mysqli_query($con, $sql_category);
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    return $categories;
}

function get_ads() {
    $con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
    $sql_ads = "SELECT lot.id as id, name, title as category, price_rate as price, image as url, date_completion as calculation_date FROM category
    INNER JOIN lot ON category.id = lot.id_category
    INNER JOIN rate ON lot.id = rate.id_lot
    WHERE lot.date_creation < lot.date_completion
    ORDER BY date_creation ASC";
    $result_ads = mysqli_query($con, $sql_ads);
    $ads = mysqli_fetch_all($result_ads, MYSQLI_ASSOC);
    return $ads;
}

function get_lots() {
    $con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
    $lot_sql = "SELECT lot.id FROM lot";
    $result_lot = mysqli_query($con, $lot_sql);
    $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
    return $lots;
}

function get_ads_lot() {
    $lot_id = filter_input(INPUT_GET, 'id');
    $con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");
    $sql_ads = "SELECT lot.id as id, title as category, name, image as url, description, date_completion as calculation_date FROM lot
        INNER JOIN category ON lot.id_category = category.id
        WHERE lot.id = $lot_id";
    $result_ads = mysqli_query($con, $sql_ads);
    $ads_lot = mysqli_fetch_all($result_ads, MYSQLI_ASSOC)[0];
    return $ads_lot;
}
