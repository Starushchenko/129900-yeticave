<form class="form form--add-lot container<?= ($errors) ? ' form--invalid' : ''?>" action="/add.php" method="post" enctype="multipart/form-data"><!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item<?= in_array('lot-name', $errors) ? ' form__item--invalid' : '' ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование</label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= $lot_name ?>" required>
            <span class="form__error"><?= in_array('lot-name', $errors) ? 'Заполните название лота' : '' ?></span>
        </div>
        <div class="form__item<?= in_array('category', $errors) ? ' form__item--invalid' : '' ?>">
            <label for="category">Категория</label>
            <select id="category" name="category" required>
                <option>Выберите категорию</option>
                <? foreach ($lots_categories as $lot_cat) : ?>
                    <option <?= ($lot_category == $lot_cat) ? 'selected' : '' ?>><?= $lot_cat ?></option>
                <? endforeach; ?>
            </select>
            <span class="form__error"><?= in_array('category', $errors) ? 'Выберите категорию лота' : '' ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide<?= in_array('message', $errors) ? ' form__item--invalid' : '' ?>">
        <label for="message">Описание</label>
        <textarea id="message" name="message" placeholder="Напишите описание лота" required><?= $lot_desc ?></textarea>
        <span class="form__error"><?= in_array('message', $errors) ? 'Описание лота обязательно для заполнения' : '' ?></span>
    </div>
    <div class="form__item form__item--file<?= in_array('photo', $errors) ? ' form__item--invalid' : '' ?>"> <!-- form__item--uploaded -->
        <label>Изображение</label>
        <div class="preview">
            <button class="preview__remove" type="button">x</button>
            <div class="preview__img">
                <img src="../img/avatar.jpg" width="113" height="113" alt="Изображение лота">
            </div>
        </div>
        <div class="form__input-file">
            <input class="visually-hidden" name="photo" type="file" id="photo2" value="<?= $lot_file ?>">
            <label for="photo2">
                <span>+ Добавить</span>
            </label>
        </div>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small<?= in_array('lot-rate', $errors) ? ' form__item--invalid' : '' ?>">
            <label for="lot-rate">Начальная цена</label>
            <input id="lot-rate" type="number" name="lot-rate" placeholder="0" required value="<?= $lot_rate ?>">
            <span class="form__error"><?= in_array('lot-rate', $errors) ? 'Введите начальную цену в формате числа' : '' ?></span>
        </div>
        <div class="form__item form__item--small<?= in_array('lot-step', $errors) ? ' form__item--invalid' : '' ?>">
            <label for="lot-step">Шаг ставки</label>
            <input id="lot-step" type="number" name="lot-step" placeholder="0" required value="<?= $lot_step ?>">
            <span class="form__error"><?= in_array('lot-step', $errors) ? 'Введите числовое значение шага ставки' : '' ?></span>
        </div>
        <div class="form__item<?= in_array('lot-date', $errors) ? ' form__item--invalid' : '' ?>">
            <label for="lot-date">Дата завершения</label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017" required value="<?= $lot_date ?>"
            <span class="form__error"><?= in_array('lot-date', $errors) ? 'Введите дату окончания аукциона' : '' ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
