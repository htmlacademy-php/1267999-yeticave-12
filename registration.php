<?php
require_once('init.php');
if ($_SESSION) {
    header("Location: index.php");
}
if ($_SESSION['name']) {
    $authorization_error;
} else {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $registration = [
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'name' => $_POST['name'],
            'message' => $_POST['message']
        ];
        $email = $registration['email'];
        $password = $registration['password'];
        $name = $registration['name'];
        $message = $registration['message'];
        $date_registration = date("Y-m-d H:i:s");
        $registration_name_length = validate_correct_length($name, MIN_VALUE, MAX_VALUE);
        $registration_message_length = validate_correct_length($message, MIN_VALUE, MAX_VALUE);
        $errors = [];
        $rules = [
            'email' => function ($email) {
                return email_validate($email);
            },
            'password' => function ($password) {
                return validate_correct_length($password, MIN_VALUE, MAX_VALUE);
            },
            'name' => function ($name) {
                return validate_correct_length($name, MIN_VALUE, MAX_VALUE);
            },
            'message' => function ($message) {
                return validate_correct_length($message, MIN_VALUE, MAX_VALUE);
            }
        ];
        foreach ($_POST as $key => $value) {
            if (array_key_exists($key, $rules)) {
                $errors[$key] = $rules[$key]($value);
            }
        }
        $users = get_users($con);
        $errors['email_repeat'] = validate_repeat_email($users, $email);
        $errors = array_filter($errors);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        if (empty($errors)) {
            $stmt = db_get_prepare_stmt($con, $users_db, $data = [$date_registration, $email, $name, $hash, $message]);
            mysqli_stmt_execute($stmt);
            header("Location: login.php");
        }
        $main_content = include_template('registration_template.php', ['errors' => $errors, 'registration' => $registration]);
        $user_registration = include_template('other_layout.php', ['content' => $main_content, 'categories' => $categories, 'title' => 'Регистрация', 'user' => $_SESSION]);
    } else {
        $main_content = include_template('registration_template.php');
        $user_registration = include_template('other_layout.php', ['content' => $main_content, 'categories' => $categories, 'title' => 'Регистрация', 'user' => $_SESSION]);
    }
    print($user_registration);
}

