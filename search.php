<?php
require_once('init.php');

if (!$con) {
    $content = include_template('404.php');
} else {
    $search = filter_input(INPUT_GET, 'search');
    $page = filter_input(INPUT_GET, 'page');
    $found = search_lot($search, $con, LIMIT_SAMPLE_LOT, $page);
    $found = get_founds_lots($found);
    $found_lots = $found['lots'];
    $array_page = $found['pages'];
    $main_content = include_template('search_template.php',
        ['found_lots' => $found_lots, 'search' => $search, 'array_page' => $array_page]);
    $content = include_template('other_layout.php',
        ['content' => $main_content, 'categories' => $categories, 'title' => 'Вход', 'user' => $user]);
}

print($content);
