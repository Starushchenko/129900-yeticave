<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <? foreach ($bets as $key => $bet) : ?>
            <tr class="rates__item">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= searchInArray($key, $lots_list, 'title')['src'] ?>" width="54" height="40" alt="Сноуборд">
                    </div>
                    <h3 class="rates__title"><a href="lot.php?id=<?= $bets[$key]['bet_index'] ?>"><?= htmlspecialchars($key) ?></a></h3>
                </td>
                <td class="rates__category">
                    <?= searchInArray($key, $lots_list, 'title')['category'] ?>
                </td>
                <td class="rates__timer">
                    <div class="timer<?= strtotime('tomorrow midnight') - strtotime('now') > 3600 ? '' : ' timer--finishing'; ?>"><?= $time_remaining ?></div>
                </td>
                <td class="rates__price">
                    <?= htmlspecialchars($bets[$key]['bet_value']) . ' ₽' ?>
                </td>
                <td class="rates__time">
                    <?= calc_time_ago($bets[$key]['bet_timestamp']) ?>
                </td>
            </tr>
        <? endforeach; ?>
    </table>
</section>