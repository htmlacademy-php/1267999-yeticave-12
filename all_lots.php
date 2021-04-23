<?php
require_once('init.php');
if (!$con) {
    $content = include_template('404.php');
} else {
    $category_get = filter_input(INPUT_GET, 'category');
    $category = get_category($con, $category_get);
    $page = filter_input(INPUT_GET, 'page');
    $found = search_lot_by_category($con, $category_get, LIMIT_SAMPLE_LOT, $page);
    $found_lots = $found['found_lots'];
    foreach ($found_lots as $key => $lot) {
        $date_completion = get_date($lot['date_completion'])['times'];
        $lot_timer = get_date($lot['date_completion'])['is_finishing'];
        $found_lots[$key]['date_completion'] = $date_completion;
        $found_lots[$key]['lot_timer'] = $lot_timer;
    }
    $count_page = $found['count_page'];
    if ($count_page > 1) {
        for ($i = 1; $i <= $count_page; $i++) {
            $array_page[] = $i;
        }
    }
    $main_content = include_template('all_lots_template.php',
        ['found_lots' => $found_lots, 'search' => $search, 'array_page' => $array_page, 'category' => $category]);
    $content = include_template('other_layout.php',
        ['content' => $main_content, 'categories' => $categories, 'title' => 'Вход', 'user' => $user]);
}
print($content);
