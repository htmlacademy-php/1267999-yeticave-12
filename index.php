<?php
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

foreach ($ads as ['calculation_date' => $calculation_date]) {
    $calculation_dates[] = $calculation_date;
}

/**
 * добавляет к цене ' ₽', в случае стоимости от 1000 устанавливает разделитель тысяч
 * @param int $price цена товара, введенная пользователем
 * @return string цена товара для объявления
 */
function get_price(int $price): string
{
    if ($price >= 1000) {
        return number_format($price, 0, '', ' ') . ' ₽';
    }
    return $price . ' ₽';
}

/**
 * возвращает количество целых часов и минут до даты окончания аукциона
 * @param string $date_ad дата окончания аукциона объявления
 * @return array массив часы, минуты до даты окончания объявления
 */
function get_date(string $date_ad): array
{
    $date_today = date("Y-m-d H:i");
    $interval = strtotime($date_ad) - strtotime($date_today);
    $hours = floor($interval / 3600);
    $time_in_hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
    $minutes = floor(($interval - ($time_in_hours * 3600)) / 60);
    $time_in_minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
    return ["$time_in_hours:$time_in_minutes"];
}
require_once ('helpers.php');
$main_content = include_template('main.php', ['ads' => $ads, 'categories' => $categories]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories]);
print($layout_content);
