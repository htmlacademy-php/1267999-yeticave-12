<?php
require_once('init.php');
if (!$_SESSION['name']) {
    http_response_code(403);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $lot = [
            'name' => $_POST['lot-name'],
            'category' => $_POST['category'],
            'message' => $_POST['message'],
            'file' => $_FILES['file'],
            'rate' => $_POST['lot-rate'],
            'step' => $_POST['lot-step'],
            'date' => $_POST['lot-date']
        ];
        $lot_name = $lot['name'];
        $lot_category = $lot['category'];
        $lot_message = $lot['message'];
        $lot_file = $lot['file'];
        $lot_rate = $lot['rate'];
        $lot_step = $lot['step'];
        $lot_date = $lot['date'];
        $lot_category_id = category_id_post($categories, $lot_category);
        $date_creation = date("Y-m-d H:i:s");
        $date_valid_separator = is_date_valid($lot_date);
        $errors = [];
        $rules = [
            'lot-name' => function ($lot_name) {
                return validate_correct_length($lot_name, MIN_VALUE, MAX_VALUE);
            },
            'message' => function ($lot_message) {
                return validate_correct_length($lot_message, MIN_VALUE, MAX_VALUE);
            },
            'lot-rate' => function ($lot_rate) {
                return validate_price($lot_rate);
            },
            'lot-step' => function ($lot_step) {
                return validate_price($lot_step);
            }
        ];
        foreach ($_POST as $key => $value) {
            if (array_key_exists($key, $rules)) {
                $errors[$key] = $rules[$key]($value);
            }
        }
        $error_category = validate_category($categories, $lot_category);
        $error_file = validate_file($lot_file, MIN_SIZE_FILE);
        $error_date = validate_date($lot_date, $date_valid_separator);
        $errors = get_errors($error_file, $error_category, $error_date, $errors);
        $errors = array_filter($errors);
        $lot_url = save_file($errors, $lot_file);
        $id_user_lot = $_SESSION['id'];
        if (empty($errors)) {
            $last_lot = add_lot_to_db($con, $lot_category_id, $id_user_lot, $date_creation, $lot_name, $lot_message,
                $lot_url, $lot_rate, $lot_date, $lot_step);
            header("Location: lot.php?id=$last_lot");
        }
        $main_content = include_template('save_lot_template.php',
            ['categories' => $categories, 'errors' => $errors, 'lot' => $lot]);
        $add_lot_content = include_template('other_layout.php', [
            'content' => $main_content,
            'categories' => $categories,
            'title' => 'Добавление лота',
            'user' => $_SESSION
        ]);
    } else {
        $main_content = include_template('save_lot_template.php', ['categories' => $categories]);
        $add_lot_content = include_template('other_layout.php',
            ['content' => $main_content, 'categories' => $categories, 'title' => 'Добавление лота', 'user' => $user]);
    }
    print($add_lot_content);
}


