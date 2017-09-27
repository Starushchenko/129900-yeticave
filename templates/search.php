<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($lots_categories as $lot_cat) : ?>
                <li class="nav__item">
                    <a href="/category_catalog.php?cat=<?= $lot_cat['id'] ?>"><?= $lot_cat['name'] ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <div class="container">
        <section class="lots">
            <?php if ($search_query === '') : ?>
                <h2> Вы ничего не ввели в строку поиска </h2>
            <?php else : ?>
                <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($search_query) ?></span>»</h2>
            <?php endif; ?>
            <ul class="lots__list">
                <?php foreach ($lots_list as $key => $lot) : ?>
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
                <?php endforeach; ?>
            </ul>
        </section>
        <?php if ($pages_count > 1) : ?>
        <ul class="pagination-list">
            <?php foreach ($pages as $key => $value) : ?>
                <li class="pagination-item <?= ($current_page == $value) ? ' pagination-item-active' : '' ?>"><a href="search.php?page=<?= $value ?>&q=<?= $search_query ?>"><?= $value ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
</main>