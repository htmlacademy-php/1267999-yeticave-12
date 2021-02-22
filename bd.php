<?php
$con = mysqli_connect("localhost", "mysql", "mysql", "yeticave");

/**
 * @return array двумерный ассоциативный массив из базы данных с названиями и символьным кодом категорий
 */
function get_categories($con) {
    $sql_category = "SELECT title, cod FROM category";
    $result_category = mysqli_query($con, $sql_category);
    $categories = mysqli_fetch_all($result_category, MYSQLI_ASSOC);
    return $categories;
}

/**
 * @return array двумерный ассоциативный массив из базы данных для отображения лотов на главной странице
 */
function get_ads($con): array
{
    $sql_ads = "SELECT lot.id as id, name, title as category, price_rate as price, image as url, date_completion as calculation_date FROM category
    INNER JOIN lot ON category.id = lot.id_category
    INNER JOIN rate ON lot.id = rate.id_lot
    WHERE lot.date_creation < lot.date_completion
    ORDER BY date_creation ASC";
    $result_ads = mysqli_query($con, $sql_ads);
    $ads = mysqli_fetch_all($result_ads, MYSQLI_ASSOC);
    return $ads;
}

/**
 * @return array двумерный ассоциативный массив из базы данных для отображения id лотов
 */
function get_lots($con): array
{
    $lot_sql = "SELECT lot.id FROM lot";
    $result_lot = mysqli_query($con, $lot_sql);
    $lots = mysqli_fetch_all($result_lot, MYSQLI_ASSOC);
    return $lots;
}

/**
 * @param int $lot_id id лота
 * @return array  ассоциативный массив из базы данных для отображения карточки лота
 */

function get_ads_lot(int $lot_id, $con): array
{
    $sql = "SELECT lot.id as id, title as category, name, image as url, description, date_completion as calculation_date FROM lot
        INNER JOIN category ON lot.id_category = category.id
        WHERE lot.id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $lot_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $ads_lot = mysqli_fetch_assoc($res);
    return $ads_lot;
}
