<?php
require_once('init.php');
if (!$con) {
    $content = include_template('404.php');
} else {
    $category_get = filter_input(INPUT_GET, 'category');
    $category = get_category($con, $category_get);
    $page = filter_input(INPUT_GET, 'page');
    $found = search_lot_by_category($con, $category_get, LIMIT_SAMPLE_LOT, $page);
    $found = get_founds_lots($found);
    $found_lots = $found['lots'];
    $array_page = $found['pages'];
    $main_content = include_template('all_lots_template.php',
        ['found_lots' => $found_lots, 'search' => $search, 'array_page' => $array_page, 'category' => $category]);
    $content = include_template('other_layout.php',
        ['content' => $main_content, 'categories' => $categories, 'title' => 'Вход', 'user' => $user]);
}
print($content);
