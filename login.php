<?php
session_start();
require_once('helpers.php');
require_once('bd.php');
$categories = get_categories($con);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = [
        'email' => $_POST['email'],
        'password' => $_POST['password']
    ];
    $email = $login['email'];
    $password = $login['password'];
    $errors = [];
    $rules = [
        'email' => function($email) {
            return email_validate($email);
        },
        'password' => function($password) {
            return validate_correct_length($password, 5, 128);
        }
    ];
    foreach ($_POST as $key => $value) {
        if (array_key_exists($key, $rules)) {
            $errors[$key] = $rules[$key]($value);
        }
    }
    $users = get_users($con);
    $errors['password_verification'] = password_verification($users, $password, $email);
    $errors = array_filter($errors);
    if (empty($errors)) {
        $_SESSION['name'] = get_user_name($users, $email);
        header("Location: index.php");
    }
    $user_login = include_template('layout_login.php', ['categories' => $categories, 'errors' => $errors]);
} else {
    $user_login = include_template('layout_login.php', ['categories' => $categories]);
}
print($user_login);
