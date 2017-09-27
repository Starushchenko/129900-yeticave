<?php
require_once ('vendor/autoload.php');
require_once('init.php');

// Получение данных из БД
$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);
$lots_count_sql_query = 'SELECT COUNT(lots.id) AS lots_count FROM lots WHERE title LIKE ? OR description LIKE ?';
$lots_data_sql_query = 'SELECT
  lots.id,
  title,
  description,
  start_price,
  image,
  finish_date,
  IFNULL(MAX(bets.bet_value), lots.start_price) AS current_price,
  COUNT(bets.lot_id)                            AS bets_count,
  category_id,
  categories.name                               AS category_name
FROM lots
  LEFT JOIN categories
    ON categories.id = lots.category_id
  LEFT JOIN bets
    ON bets.lot_id = lots.id
WHERE title LIKE ? OR description LIKE ?
GROUP BY lots.id
ORDER BY lots.create_date DESC
LIMIT 9 OFFSET ?';

if (empty($_GET['q'])) {
    $_GET['q'] = null;
}
$search_query = '%' . strval($_GET['q']) . '%';
$current_page = $_GET['page'] ?? 1;
if (empty((int) $current_page) || $current_page === 1) {
    $offset = 0;
} else {
    $offset = ((int) $current_page - 1) * 9;
}
$lots_count = get_mysql_data($connect, $lots_count_sql_query, [$search_query, $search_query])[0]["lots_count"];
$pages_count = ceil($lots_count / 9);
$pages = range(1, $pages_count);
$lots_list = get_mysql_data($connect, $lots_data_sql_query, [$search_query, $search_query, $offset]);

// Компиляция шаблона страницы
$page_content = render_template('search', [
    'lots_categories' => $lots_categories,
    'lots_list' => $lots_list,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'search_query' => strval($_GET['q']),
    'current_page' => $current_page
]);

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Поиск по запросу "' . strval($_GET['q']) . '"',
    'is_auth' => $is_auth,
    'user' => $user,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);