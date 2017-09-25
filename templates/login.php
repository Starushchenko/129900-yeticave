<nav class="nav">
    <ul class="nav__list container">
        <? foreach ($lots_categories as $lot_cat) : ?>
            <li class="nav__item">
                <a href="all-lots.html"><?= $lot_cat['name'] ?></a>
            </li>
        <? endforeach; ?>
    </ul>
</nav>
<form class="form container<?= isset($form_valid) ? '' : ' form--invalid' ?>" action="/login.php" method="post"> <!-- form--invalid -->
    <? if (isset($after_signup_message)) : ?>
         <p>Теперь вы можете войти, используя свой email и пароль.</p>
    <? endif; ?>
    <h2>Вход</h2>
    <div class="form__item<?= ($form_data['email']['valid']) ? '' : ' form__item--invalid' ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail*</label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?= $form_data['email']['value']?>">
        <span class="form__error"><?= $form_data['email']['error_message'] ?></span>
    </div>
    <div class="form__item form__item--last<?= ($form_data['password']['valid']) ? '' : ' form__item--invalid' ?>">
        <label for="password">Пароль*</label>
        <input id="password" type="text" name="password" placeholder="Введите пароль" value="<?= $form_data['password']['value'] ?>">
        <span class="form__error"><?= $form_data['password']['error_message'] ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>