<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($my_bets as $my_bet): ?>
            <tr class="rates__item<?php if ($my_bet['bet_win'] && $my_bet['auction_over']): ?> rates__item--win<?php elseif (!$my_bet['bet_win'] && $my_bet['auction_over']): ?>  rates__item--end<?php endif; ?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $my_bet['image']; ?>" width="54" height="40" alt="<?= $my_bet['title']; ?>">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= $my_bet['lot_id']; ?>"><?= $my_bet['name']; ?></a>
                    </h3>
                    <?php if ($my_bet['bet_win']): ?>
                        <p>Телефон +7 900 667-84-48, Скайп: Vlas92. Звонить с 14 до 20</p>
                    <?php endif ?>
                </td>
                <td class="rates__category">
                    <?= $my_bet['title']; ?>
                </td>
                <td class="rates__timer">
                    <?php if ($my_bet['auction_over'] && $my_bet['bet_win']): ?>
                        <div class="timer timer--win">Ставка выиграла</div>
                    <?php elseif ($my_bet['auction_over'] && !$my_bet['bet_win']): ?>
                        <div class="timer timer--end">Торги окончены</div>
                    <?php else: ?>
                        <div class="timer<?php if ($my_bet['lot_timer']): ?> timer--finishing<?php endif; ?>">
                            <?= $my_bet['calculation_date']; ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td class="rates__price">
                    <?= $my_bet['price_rate']; ?> р
                </td>
                <td class="rates__time">
                    <?= $my_bet['date_rate']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>
