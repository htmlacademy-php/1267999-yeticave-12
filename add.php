<?php
require_once ('helpers.php');
require_once ('bd.php');

$categories = get_categories($con);
$id_category_lot = get_category_lot($con, $_POST['category']);
$error_category = validateCategory($id_category_lot);
$errors_rules = [];
$rules = [
    'lot-name' => function() {
        return isCorrectLength('lot-name', 5, 128);
    },
    'message' => function() {
        return isCorrectLength('message', 20, 512);
    },
    'lot-rate' => function() {
        return validatePrice('lot-rate');
    },
    'lot-step' => function() {
        return validatePrice('lot-step');
    },
    'lot-date' => function() {
        return validateDate();
    }
];
foreach ($_POST as $key => $value) {
    if (isset($rules[$key])) {
        $rule = $rules[$key];
        $errors_rules[$key] = $rule();
    }
}
$error_file = validateFile();
print_r($error_file);
$errors_rules = array_filter($errors_rules);
$errors = get_errors($error_file, $error_category, $errors_rules);
$errors = array_filter($errors);
$errors_form = empty($errors) ? "" : " form--invalid";
$lot_url = save_file($errors);
$errors_lot_class = empty($errors['lot-name']) ? "" : " form__item--invalid";
$errors_message_class = empty($errors['message']) ? "" : " form__item--invalid";
$errors_lot_rate_class = empty($errors['lot-rate']) ? "" : " form__item--invalid";
$errors_lot_step_class = empty($errors['lot-step']) ? "" : " form__item--invalid";
$errors_lot_date_class = empty($errors['lot-date']) ? "" : " form__item--invalid";
$errors_file_class = empty($errors['file']) ? "" : " form__item--invalid";
$errors_category_class = empty($errors['category']) ? "" : " form__item--invalid";
//$add_sql_lot = get_sql_lot($con, $id_category_lot, $lot_url);

if (empty($errors) and $_SERVER['REQUEST_METHOD'] == 'POST') {
    get_sql_lot($con, $id_category_lot, $lot_url);
    $last_lot = get_lots($con);
    $last_lot = array_key_last($last_lot) + 1;
    header("Location: lot.php?id=$last_lot");
}

$add_lot_content = include_template('add_lot.php', ['categories' => $categories, 'errors' => $errors, 'errors_form' => $errors_form, 'errors_lot_class' => $errors_lot_class, 'errors_message_class' => $errors_message_class, 'errors_lot_rate_class' => $errors_lot_rate_class, 'errors_lot_step_class' => $errors_lot_step_class, 'errors_lot_date_class' => $errors_lot_date_class, 'errors_file_class' => $errors_file_class, 'errors_category_class' => $errors_category_class]);
print($add_lot_content);
