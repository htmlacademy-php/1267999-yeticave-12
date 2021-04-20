<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= $winner_lot['user.name']; ?></p>
<p>Ваша ставка для лота <a href="<?= $winner_lot['image']; ?>"><?= $winner_lot['lot.name']; ?></a> победила.</p>
<p>Перейдите по ссылке <a href="http://localhost/yeticave/my_bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
</body>
</html>

