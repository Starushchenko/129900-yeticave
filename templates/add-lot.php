<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($lots_categories as $lot_cat) : ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= $lot_cat ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </nav>
    <form class="form form--add-lot container<?= ($form_valid) ? '' : ' form--invalid' ?>" action="/add.php"
          method="post"
          enctype="multipart/form-data"><!-- form--invalid -->
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item<?= ($form_data['lot-name']['valid']) ? '' : ' form__item--invalid' ?>">
                <!-- form__item--invalid -->
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота"
                       value="<?= $form_data['lot-name']['value'] ?>">
                <span class="form__error"><?= $form_data['lot-name']['valid'] ? '' : 'Заполните название лота' ?></span>
            </div>
            <div class="form__item<?= $form_data['category']['valid'] ? '' : ' form__item--invalid' ?>">
                <label for="category">Категория</label>
                <select id="category" name="category">
                    <option>Выберите категорию</option>
                    <? foreach ($lots_categories as $lot_cat) : ?>
                        <option <?= ($form_data['category']['value'] == $lot_cat) ? 'selected' : '' ?>><?= $lot_cat ?></option>
                    <? endforeach; ?>
                </select>
                <span class="form__error"><?= $form_data['category']['valid'] ? '' : 'Выберите категорию лота' ?></span>
            </div>
        </div>
        <div class="form__item form__item--wide<?= $form_data['message']['valid'] ? '' : ' form__item--invalid' ?>">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота"
            ><?= $form_data['message']['value'] ?></textarea>
            <span class="form__error"><?= $form_data['message']['valid'] ? '' : 'Описание лота обязательно для заполнения' ?></span>
        </div>
        <div class="form__item form__item--file<?= $form_data['img_url']['valid'] ? ' form__item--uploaded' : ''; ?> ">
            <!-- form__item--uploaded -->
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="<?= htmlspecialchars($add_item_form['img_url']['value']); ?>" width="113"
                         height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" name="photo" type="file" id="photo2"
                       value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
            <span class="form__error"><?= $form_data['img_url']['valid'] ? '' : 'Необходимо прикрепить jpeg-изображение не более 500 Кб' ?></span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small<?= $form_data['lot-rate']['valid'] ? '' : ' form__item--invalid' ?>">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="lot-rate" placeholder="0"
                       value="<?= $form_data['lot-rate']['value'] ?>">
                <span class="form__error"><?= $form_data['lot-rate']['valid'] ? '' : 'Введите начальную цену в формате числа' ?></span>
            </div>
            <div class="form__item form__item--small<?= $form_data['lot-step']['valid'] ? '' : ' form__item--invalid' ?>">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="lot-step" placeholder="0"
                       value="<?= $form_data['lot-step']['value'] ?>">
                <span class="form__error"><?= $form_data['lot-step']['valid'] ? '' : 'Введите числовое значение шага ставки' ?></span>
            </div>
            <div class="form__item<?= $form_data['lot-date']['valid'] ? '' : ' form__item--invalid' ?>">
                <label for="lot-date">Дата завершения</label>
                <input class="form__input-date" id="lot-date" type="text" name="lot-date" placeholder="20.05.2017"
                
                       value="<?= $form_data['lot-date']['value'] ?>"
                <span class="form__error"><?= $form_data['lot-date']['valid'] ? '' : 'Введите дату окончания аукциона' ?></span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
</main>