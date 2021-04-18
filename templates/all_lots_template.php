<div class="container">
    <section class="lots">
        <h2>Все лоты в категории <span>«<?= $category['title']; ?>»</span></h2>
        <?php if ($found_lots): ?>
            <ul class="lots__list">
                <?php foreach ($found_lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= htmlspecialchars($lot['url']); ?>" width="350" height="260"
                                 alt="<?= htmlspecialchars($lot['category']); ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= $lot['category']; ?></span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php?id=<?= htmlspecialchars($lot['id']); ?>"><?= htmlspecialchars($lot['name']); ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= htmlspecialchars($lot['price_starting']); ?><b
                                            class="rub">р</b></span>
                                </div>
                                <div
                                    class="lot__timer timer<?php if ($lot['lot_timer']): ?> timer--finishing<?php endif; ?>">
                                    <?= htmlspecialchars($lot['date_completion']); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <h2>Ничего не найдено по вашему запросу</h2>
        <?php endif; ?>
    </section>
    <?php if ($array_page): ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <?php foreach ($array_page as $page): ?>
                <li class="pagination-item pagination-item-active"><a
                        href="all-lots.php?page=<?= $page; ?>&category=<?= $category; ?>"><?= $page; ?></a></li>
            <?php endforeach; ?>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
    <?php endif; ?>
</div>
