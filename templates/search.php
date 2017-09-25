<main>
    <nav class="nav">
        <ul class="nav__list container">
            <? foreach ($lots_categories as $lot_cat) : ?>
                <li class="nav__item">
                    <a href="all-lots.html"><?= $lot_cat['name'] ?></a>
                </li>
            <? endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <? if ($search_query === '') : ?>
                <h2> Вы ничего не ввели в строку поиска </h2>
            <? else : ?>
                <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search_query) ?></span>»</h2>
            <? endif; ?>
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
                                <div class="lot__timer timer <?= ((strtotime($lot['finish_date']) - strtotime('now')) > 0 && (strtotime($lot['finish_date']) - strtotime('now')) < 3600) ? ' timer timer--finishing' : '' ?>">
                                    <?= calc_time_to_end(strtotime($lot['finish_date'])); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </section>
        <? if ($pages_count > 1) : ?>
        <ul class="pagination-list">
            <? foreach ($pages as $key => $value) : ?>
                <li class="pagination-item <?= ($current_page == $value) ? ' pagination-item-active' : '' ?>"><a href="search.php?page=<?= $value ?>&q=<?= $search_query ?>"><?= $value ?></a></li>
            <? endforeach; ?>
        </ul>
        <? endif; ?>
    </div>
</main>