<?php
$users_lots = get_users_lots($con, $lot_id);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cost = $_POST['cost'];
    $today = date("Y-m-d H:i");
    $errors = [];
    $errors['positive_integer'] = validate_price($cost);
    $errors['price_validation'] = bid_correctness($cost, $ads_lot['min_rate']);
    $errors['availability'] = (get_post_val($cost)) ? false : true;
    $errors = array_filter($errors);
    if (empty($errors)) {
        $user_id = $_SESSION['id'];
        add_user_rate_to_db ($con, $user_id, $lot_id, $today, $cost);
        header("Location: lot.php?id=$lot_id");
    }
}

