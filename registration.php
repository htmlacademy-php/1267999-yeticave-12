<?php
require_once('helpers.php');
require_once ('bd.php');
$categories = get_categories($con);
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
    $registration_name_length = validate_correct_length($name, 5, 128);
    $registration_message_length = validate_correct_length($message, 5, 512);
    $errors = [];
    $rules = [
        'email' => function($email) {
            return email_validate($email);
        },
        'password' => function($password) {
            return validate_password($password);
        },
        'name' => function($name) {
            return validate_correct_length($name, 5, 128);
        },
        'message' => function($message) {
            return validate_correct_length($message, 5, 512);
        }
    ];
    foreach ($_POST as $key => $value) {
        if (array_key_exists($key, $rules)) {
            $errors[$key] = $rules[$key]($value);
        }
    }
    $password_validate_length = validate_correct_length($password, 5, 128);
    $errors['password_length'] = $password_validate_length;
    $users = get_users($con);

    $errors['email_repeat'] = validate_email($users, $email);
    $errors = array_filter($errors);
    $hash = password_hash($password, PASSWORD_DEFAULT);
    if (empty($errors)) {
        $stmt = db_get_prepare_stmt($con, $users_bd, $data= [$date_registration, $email, $name, $password, $message]);
        mysqli_stmt_execute($stmt);
        header("Location: registration.php");
    }
    $user_registration = include_template('sign-up.php', ['categories' => $categories, 'errors' => $errors, 'registration' => $registration]);
} else {
    $user_registration = include_template('sign-up.php', ['categories' => $categories]);
}
print($user_registration);

