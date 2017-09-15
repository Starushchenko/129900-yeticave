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
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot_title) ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot_image) ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot_category) ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot_desc) ?></p>
            </div>
            <div class="lot-item__right">
                <? if ($is_auth) : ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        10:54:12
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= htmlspecialchars($lot_price) ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span>12 000 р</span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="https://echo.htmlacademy.ru" method="post">
                        <p class="lot-item__form-item">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="number" name="cost" placeholder="12 000">
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
                <? endif; ?>
                <div class="history">
                    <h3>История ставок (<span>4</span>)</h3>
                    <table class="history__list">
                        <? foreach ($bets as $key => $bet) : ?>
                            <tr class="history__item">
                                <td class="history__name"><?= htmlspecialchars($bet["name"]) ?></td>
                                <td class="history__price"><?= htmlspecialchars($bet["price"]) ?>р</td>
                                <td class="history__time"><?= calc_time_ago($bet["ts"]) ?></td>
                            </tr>
                        <? endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </section>
</main>