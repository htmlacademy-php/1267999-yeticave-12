<?php
require_once('init.php');
if (!$con) {
    $content = include_template('404.php');
} else {
    $user_id = $_SESSION['id'];
    $my_bets = get_my_bets($con, $user_id);
    foreach ($my_bets as $key => $value) {
        $date_completion = get_date($value['date_completion'])['times'];
        $lot_timer = get_date($value['date_completion'])['is_finishing'];
        $my_bets[$key]['calculation_date'] = $date_completion;
        $my_bets[$key]['lot_timer'] = $lot_timer;
        $my_bets[$key]['auction_over'] = get_auction_over($value['date_completion']);
        $max_lot_bet = get_max_bet($con, $my_bets[$key]['lot_id'])[0]['price_rate'];
        $my_lot_rate = $my_bets[$key]['price_rate'];;
        if ($max_lot_bet == $my_lot_rate && $my_bets[$key]['auction_over']) {
            $my_bets[$key]['bet_win'] = true;
        } else {
            $my_bets[$key]['bet_win'] = false;
        }
    }
    $main_content = include_template('my_bets.php', ['my_bets' => $my_bets]);
    $content = include_template('other_layout.php', ['content' => $main_content, 'categories' => $categories, 'user' => $_SESSION, 'title' => 'Мои ставки']);
}
print($content);
