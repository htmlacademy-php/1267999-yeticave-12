<?php
$is_auth = rand(0, 1);
$user_name = 'Konstantin'; // укажите здесь ваше имя
$categories = [
    'boards' => 'Доски и лыжи',
    'mounts' => 'Крепления',
    'boots' => 'Ботинки',
    'clothes' => 'Одежда',
    'tools' => 'Инструменты',
    'various' => 'Разное'
];
$ads = [
    [
        'name' => '2014 Rossignol District Snowboard',
        'category' => $categories['boards'],
        'price' => 10999,
        'url' => 'img/lot-1.jpg',
        'calculation_date' => '2020-12-16'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories['boards'],
        'price' => 159999,
        'url' => 'img/lot-2.jpg',
        'calculation_date' => '2020-12-21'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories['mounts'],
        'price' => 8000,
        'url' => 'img/lot-3.jpg',
        'calculation_date' => '2020-12-22'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories['boots'],
        'price' => 10999,
        'url' => 'img/lot-4.jpg',
        'calculation_date' => '2020-12-23'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories['clothes'],
        'price' => 7500,
        'url' => 'img/lot-5.jpg',
        'calculation_date' => '2020-12-24'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => $categories['various'],
        'price' => 5400,
        'url' => 'img/lot-6.jpg',
        'calculation_date' => '2020-12-25'
    ]
];

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
    return $time = [$time_in_hours, $time_in_minutes];
}
$time = get_date($ads['0']['calculation_date']);
require_once ('helpers.php');
$main_content = include_template('main.php', ['ads' => $ads]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories]);
print($layout_content);
