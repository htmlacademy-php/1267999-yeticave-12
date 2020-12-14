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
        'url' => 'img/lot-1.jpg'
    ],
    [
        'name' => 'DC Ply Mens 2016/2017 Snowboard',
        'category' => $categories['boards'],
        'price' => 159999,
        'url' => 'img/lot-2.jpg'
    ],
    [
        'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'category' => $categories['mounts'],
        'price' => 8000,
        'url' => 'img/lot-3.jpg'
    ],
    [
        'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'category' => $categories['boots'],
        'price' => 10999,
        'url' => 'img/lot-4.jpg'
    ],
    [
        'name' => 'Куртка для сноуборда DC Mutiny Charocal',
        'category' => $categories['clothes'],
        'price' => 7500,
        'url' => 'img/lot-5.jpg'
    ],
    [
        'name' => 'Маска Oakley Canopy',
        'category' => $categories['various'],
        'price' => 5400,
        'url' => 'img/lot-6.jpg'
    ]
];

/**
 * добавляет к цене ' ₽', в случае стоимости от 1000 устанавливает разделитель тысяч
 * @param $price int цена товара, введенная пользователем
 * @return string цена товара для объявления
 */
function get_price(int $price): string
{
    if ($price >= 1000) {
        return number_format($price, 0, '', ' ') . ' ₽';
    }
    return $price . ' ₽';
}
require_once ('helpers.php');
$main_content = include_template('main.php', ['ads' => $ads]);
$layout_content = include_template('layout.php', ['content' => $main_content, 'title' => 'Yeticave - Главная', 'categories' => $categories]);
print($layout_content);
