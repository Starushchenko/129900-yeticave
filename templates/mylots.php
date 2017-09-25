<section class="rates container">
    <h2>Мои ставки</h2>
    <? if (!empty($bets)) : ?>
        <table class="rates__list">
            <? foreach ($bets as $key => $bet) : ?>
                <tr class="rates__item<?= (strtotime('now') > strtotime($bet['finish_date'])) ? ' rates__item--end' : ''?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="<?= $bet['image'] ?>" width="54" height="40" alt="Сноуборд">
                        </div>
                        <h3 class="rates__title">
                            <a href="lot.php?id=<?= $bet['lot_id'] ?>"><?= htmlspecialchars($bet['title']) ?></a></h3>
                    </td>
                    <td class="rates__category">
                        <?= $bet['category'] ?>
                    </td>
                    <td class="rates__timer">
                        <div class="timer<?= (strtotime($bet['finish_date']) - strtotime('now')) < 3600 && (strtotime($bet['finish_date']) - strtotime('now')) > 0 ? ' timer--finishing' : ''; ?>"><?= calc_time_to_end(strtotime($bet['finish_date'])) ?></div>
                    </td>
                    <td class="rates__price">
                        <?= htmlspecialchars($bet['bet_value']) . ' ₽' ?>
                    </td>
                    <td class="rates__time">
                        <?= calc_time_ago(strtotime($bet['bet_date'])) ?>
                    </td>
                </tr>
            <? endforeach; ?>
        </table>
    <? else : ?>
        <p>Вы пока не сделали ставок</p>
    <? endif; ?>
</section>