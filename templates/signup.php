<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($lots_categories as $lot_cat) : ?>
                <li class="nav__item">
                    <a href="/category_catalog.php?cat=<?= $lot_cat['id'] ?>"><?= $lot_cat['name'] ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </nav>
    <form class="form container<?= ($form_valid) ? '' : ' form--invalid' ?>" action="/signup.php" enctype="multipart/form-data" method="post"> <!-- form--invalid -->
        <h2>Регистрация нового аккаунта</h2>
        <div class="form__item<?= ($form_data['email']['valid']) ? '' : ' form__item--invalid' ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $form_data['email']['value'] ?>" >
            <span class="form__error"><?= $form_data['email']['error_text'] ?></span>
        </div>
        <div class="form__item<?= ($form_data['password']['valid']) ? '' : ' form__item--invalid' ?>">
            <label for="password">Пароль*</label>
            <input id="password" type="text" name="password" placeholder="Введите пароль"  value="<?= $form_data['password']['value'] ?>">
            <span class="form__error"><?= $form_data['password']['valid'] ? '' : 'Придумайте пароль' ?></span>
        </div>
        <div class="form__item<?= ($form_data['name']['valid']) ? '' : ' form__item--invalid' ?>">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="name" placeholder="Введите имя"  value="<?= $form_data['name']['value'] ?>">
            <span class="form__error"><?= $form_data['password']['valid'] ? '' : 'Укажите своё имя' ?></span>
        </div>
        <div class="form__item<?= ($form_data['contacts']['valid']) ? '' : ' form__item--invalid' ?>">
            <label for="message">Контактные данные*</label>
            <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться" ><?= $form_data['contacts']['value'] ?></textarea>
            <span class="form__error"><?= $form_data['contacts']['valid'] ? '' : 'Укажите ваши контактные данные' ?></span>
        </div>
        <div class="form__item form__item--file form__item--last<?= is_string($file_valid) ? ' form__item--uploaded' : ' form__item--invalid'; ?> ">
            <label>Изображение</label>
            <div class="preview">
                <button class="preview__remove" type="button">x</button>
                <div class="preview__img">
                    <img src="<?= is_string($file_valid) ? $file_valid : ''; ?>" width="113" height="113" alt="Изображение лота">
                </div>
            </div>
            <div class="form__input-file">
                <input class="visually-hidden" name="avatar" type="file" id="photo2" value="">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
            <span class="form__error"><?= $file_valid ? '' : 'Необходимо прикрепить jpeg- или png-изображение не более 500 Кб' ?></span>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="/login.php">Уже есть аккаунт</a>
    </form>
</main>