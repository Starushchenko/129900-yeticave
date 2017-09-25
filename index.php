<?php

require_once('init.php');

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user = $_SESSION['user'];
} else {
    $is_auth = false;
    $user = false;
}
// Рассчет времени до окончания текущих суток
$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);

$lots_sql_query = 'SELECT
  lots.id,
  title,
  start_price,
  image,
  finish_date,
  IFNULL(MAX(bets.bet_value), lots.start_price) AS current_price,
  COUNT(bets.lot_id)                            AS bets_count,
  category_id,
  categories.name AS category_name
FROM lots
  LEFT JOIN categories
    ON categories.id = lots.category_id
  LEFT JOIN bets
    ON bets.lot_id = lots.id
WHERE lots.finish_date > NOW()
GROUP BY lots.id
ORDER BY lots.create_date DESC';

$lots_list = get_mysql_data($connect, $lots_sql_query, []);

// Компиляция шаблона страницы
$page_content = render_template('index',
    ['lots_categories' => $lots_categories, 'lots_list' => $lots_list]);

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user' => $user,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);
?>
