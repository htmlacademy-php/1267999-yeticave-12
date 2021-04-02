<?php
$users_lots = get_users_lots($con, $lot_id);
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cost = $_POST['cost'];
    $today = date("Y-m-d H:i");
    $errors = [];
    $errors['positive_integer'] = validate_price($cost);
    $errors['price_validation'] = bid_correctness($cost, $ads_lot['min_rate']);
    if (get_post_val($cost)) {
        $errors['availability'] = false;
    } else {
        $errors['availability'] = true;
    }
    $errors = array_filter($errors);
    if (empty($errors)) {
        $user_id = $_SESSION['id'];
        $stmt = db_get_prepare_stmt($con, $add_rate, $data = [$user_id, $lot_id, $today, $cost]);
        mysqli_stmt_execute($stmt);
        header("Location: lot.php?id=$lot_id");
    }
}

