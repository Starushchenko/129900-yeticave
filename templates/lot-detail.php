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
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['title']) ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot['image']) ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['category']) ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description']) ?></p>
            </div>
            <div class="lot-item__right">
                <? if ($is_auth && !$bet_is_made && !$user_is_author && (strtotime($lot['finish_date'])) > strtotime('now')) : ?>
                    <div class="lot-item__state">
                        <div class="lot-item__timer timer">
                            <?= calc_time_to_end(strtotime($lot['finish_date'])); ?>
                        </div>
                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= htmlspecialchars($lot['lot_price']) ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= htmlspecialchars($lot['lot_price'] + $lot['bet_step']) ?></span>
                            </div>
                        </div>
                        <form class="lot-item__form" action="/lot.php?id=<?= $lot['lot_id'] ?>" method="post">
                            <p class="lot-item__form-item">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="cost" placeholder="<?= htmlspecialchars($lot['lot_price'] + $lot['bet_step']) ?>">
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                        <span style="font-size: 11px;color: #f84646;"><?= (isset($form_data['cost'])) ? $form_data['cost']['error_text'] : '' ?></span>
                    </div>
                <? elseif ($is_auth && (strtotime($lot['finish_date'])) <= strtotime('now')) : ?>
                    <div class="lot-item__state">
                        <p>Аукцион окончен</p>
                        <? if (isset($winner)) : ?>
                            <p>Победитель - <?= $winner ?></p>
                        <? endif; ?>
                    </div>
                <? elseif ($is_auth && $bet_is_made) : ?>
                    <div class="lot-item__state">
                        <p>Вы уже сделали ставку по этому лоту</p>
                        <a class="button" href="mylots.php">Мои ставки</a>
                    </div>
                <? elseif ($is_auth && $user_is_author) : ?>
                    <div class="lot-item__state">
                        <p>Вы являетесь автором лота</p>
                    </div>
                <? else : ?>
                    <div class="lot-item__state">
                        <p>Чтобы сделать ставку, необходимо <a href="/login.php">авторизоваться</a></p>
                    </div>
                <? endif; ?>
                <div class="history">
                    <h3>История ставок (<span><?= $bets_count ?></span>)</h3>
                    <table class="history__list">
                        <? foreach ($bets as $key => $bet) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($bet["user_name"]) ?></td>
                                <td class="history__price"><?= htmlspecialchars($bet["bet_value"]) ?>р</td>
                                <td class="history__time"><?= calc_time_ago(strtotime($bet["bet_date"])) ?></td>
                            </tr>
                        <? endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>