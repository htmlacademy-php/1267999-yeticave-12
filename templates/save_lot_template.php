<form class="form form--add-lot container<?= empty($errors) ? "" : " form--invalid"; ?>" action="add_lot.php"
      enctype="multipart/form-data" method="post"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item<?= empty($errors['lot-name']) ? "" : " form__item--invalid"; ?>">
            <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                   value="<?= htmlspecialchars(get_post_val($lot['name'])); ?>">
            <span class="form__error"><?= $errors['lot-name'] ?? ""; ?></span>
        </div>
        <div class="form__item<?= empty($errors['category']) ? "" : " form__item--invalid"; ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option><?= get_post_category($lot['category']); ?></option>
                <?php foreach ($categories as $category): ?>
                    <option id="<?= htmlspecialchars($category['id']); ?>"><?= htmlspecialchars($category['title']); ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?= $errors['category'] ?? ""; ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide<?= empty($errors['message']) ? "" : " form__item--invalid"; ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message"
                  placeholder="Напишите описание лота"><?= htmlspecialchars(get_post_val($lot['message'])); ?></textarea>
        <span class="form__error"><?= $errors['message'] ?? ""; ?></span>
    </div>
    <div class="form__item form__item--file<?= empty($errors['file']) ? "" : " form__item--invalid"; ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="file" value="">
            <label for="lot-img">
                Добавить
            </label>
        </div>
        <span class="form__error"><?= $errors['file'] ?? ""; ?></span>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small<?= empty($errors['lot-rate']) ? "" : " form__item--invalid"; ?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= get_post_val($lot['rate']); ?>">
            <span class="form__error"><?= $errors['lot-rate'] ?? ""; ?></span>
        </div>
        <div class="form__item form__item--small<?= empty($errors['lot-step']) ? "" : " form__item--invalid"; ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= get_post_val($lot['step']); ?>">
            <span class="form__error"><?= $errors['lot-step']; ?></span>
        </div>
        <div class="form__item<?= empty($errors['lot-date']) ? "" : " form__item--invalid"; ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД" value="<?= get_post_val($lot['date']); ?>">
            <span class="form__error"><?= $errors['lot-date']; ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>

