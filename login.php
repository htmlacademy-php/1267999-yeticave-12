<?php
require_once('init.php');
if ($_SESSION) {
    header("Location: index.php");
}
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
            return validate_correct_length($password, MIN_VALUE, MAX_VALUE);
        }
    ];
    foreach ($_POST as $key => $value) {
        if (array_key_exists($key, $rules)) {
            $errors[$key] = $rules[$key]($value);
        }
    }
    $users = get_users($con);

    $user_information = get_user_information($email, $con);
    $user_password_hash = $user_information['password'];
    $errors['password_verification'] = password_verification($user_information, $email, $password);
    $errors = array_filter($errors);
    if (empty($errors)) {
        $_SESSION['name'] = $user_information['name'];
        $_SESSION['id'] = $user_information['id'];
        header("Location: index.php");
    }
    $main_content = include_template('login_template.php', ['errors' => $errors]);
    $user_login = include_template('other_layout.php', ['content' => $main_content, 'categories' => $categories, 'title' => 'Вход', 'user' => $_SESSION]);
} else {
    $main_content = include_template('login_template.php');
    $user_login = include_template('other_layout.php', ['content' => $main_content, 'categories' => $categories, 'title' => 'Вход', 'user' => $_SESSION]);
}
print($user_login);
