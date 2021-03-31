<section class="lot-item container">
    <h2><?= $ads_lot['category']; ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $ads_lot['url']; ?>" width="730" height="548" alt="<?= $ads_lot['category']; ?>">
            </div>
            <p class="lot-item__category">Категория: <span><?= $ads_lot['category']; ?></span></p>
            <p class="lot-item__description"></p><?= $ads_lot['description']; ?></p>
        </div>
        <div class="lot-item__right">
            <?php if ($user['name']): ?>
                <div class="lot-item__state">
                    <div
                        class="lot-item__timer timer<?php if ($ads_lot['lot_timer']): ?> timer--finishing<?php endif ?>">
                        <?= ($ads_lot['calculation_date']); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= $ads_lot['price_rate'] ?? $ads_lot['price_starting']; ?> </span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= $ads_lot['min_rate']; ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form<?= empty($errors) ? "" : " form--invalid"; ?>" action="" method="post">
                        <p class="lot-item__form-item form__item<?= empty($errors['positive_integer'] || $errors['price_validation']) ? "" : " form__item--invalid"; ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="cost" placeholder="<?= $ads_lot['min_rate']; ?>">
                            <?php if ($errors['availability']): ?>
                                <span class="form__error">Сделайте ставку</span>
                            <?php else: ?>
                                <span class="form__error"><?= $errors['positive_integer'] ?? ""; ?></span>
                                <span class="form__error"><?= $errors['price_validation'] ?? ""; ?></span>
                            <?php endif; ?>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= count($users_lots); ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($users_lots as $lot): ?>
                    <tr class="history__item">
                        <td class="history__name"><?= $lot['name']; ?></td>
                        <td class="history__price"><?= $lot['price_rate']; ?> р</td>
                        <td class="history__time"><?= $lot['date_rate']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>

