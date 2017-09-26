<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное
            снаряжение.</p>
        <ul class="promo__list">
            <? foreach ($lots_categories as $lot_cat) : ?>
                <li class="promo__item promo__item--<?= $lot_cat['class'] ?>">
                    <a class="promo__link" href="category_catalog.php?cat=<?= $lot_cat['id'] ?>"><?= $lot_cat['name'] ?></a>
                </li>
            <? endforeach; ?>
            </li>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
            <select class="lots__select">
                <? foreach ($lots_categories as $lot_cat) {
                    print("<option>$lot_cat[name]</option>");
                } ?>
            </select>
        </div>
        <ul class="lots__list">
            <? foreach ($lots_list as $key => $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot["image"] ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot["category_name"] ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="lot.php?id=<?= $lot["id"] ?>"><?= htmlspecialchars($lot["title"]) ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= htmlspecialchars($lot["start_price"]) ?><b
                                            class="rub">р</b></span>
                            </div>
                            <div class="lot__timer timer">
                                <?= calc_time_to_end(strtotime($lot['finish_date'])); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    </section>
</main>