<?php
require_once('init.php');
require_once('vendor/autoload.php');
$winner_lots = getwinner_lots($con);
if ($winner_lots) {
    $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
    $transport->setUsername("keks@phpdemo.ru");
    $transport->setPassword("htmlacademy");
    $mailer = new Swift_Mailer($transport);
    foreach ($winner_lots as $key => $winner_lot) {
        $winner_name = $winner_lots[$key]['user.name'];
        $winner_email = $winner_lots[$key]['email'];
        $message = new Swift_Message();
        $message->setSubject("Победитель аукциона");
        $message->setFrom(['keks@phpdemo.ru' => 'Yeticave']);
        $message->addTo($winner_email, $winner_name);
        $msg_content = include_template('email.php', ['winner_lot' => $winner_lot]);
        $message->setBody($msg_content, 'text/html');
        $result = $mailer->send($message);
        update_winner_lots($con, $winner_lots[$key]['id_lot']);
    }
}