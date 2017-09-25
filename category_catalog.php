<?php

require_once('init.php');

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user = $_SESSION['user'];
} else {
    $is_auth = false;
}

// Получение данных из БД
$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);
$lots_count_sql_query = 'SELECT COUNT(lots.id) AS lots_count FROM lots WHERE category_id = ?';
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
WHERE category_id = ?
GROUP BY lots.id
ORDER BY lots.create_date DESC
LIMIT 9 OFFSET ?';

if (empty($_GET['cat'])) {
    $_GET['cat'] = 1;
}

/**
 * Вывод лотов по категории, id которой указан в get-атрибуте 'cat'
 * Если атрибут пуст, по умолчанию показывать лоты из категории 1
 * На странице показывается 9 лотов. Если количество лотов, подходящих по параметрам ($lots_count) больше 9,
 * показывается пагинация, где $current_page - текущая страница, $pages_count - кол-во страниц, исходя из кол-ва
 * элементов, $pages - массив страниц на основе количества.
 */
$search_query = strval($_GET['cat']);
$current_page = $_GET['page'] ?? 1;
if (empty($current_page) || $current_page === 1) {
    $offset = 0;
} else {
    $offset = ($current_page - 1) * 9;
}
$lots_count = get_mysql_data($connect, $lots_count_sql_query, [$search_query])[0]["lots_count"];
$pages_count = ceil($lots_count / 9);
$pages = range(1, $pages_count);

// Получение лотов и название категории по заданному id категории
$lots_list = get_mysql_data($connect, $lots_data_sql_query, [$search_query, $offset]);
$category_name = get_mysql_data($connect, 'SELECT name FROM categories WHERE id = ?', [$search_query])[0]['name'];

// Компиляция шаблона страницы
$page_content = render_template('category_catalog', [
    'lots_categories' => $lots_categories,
    'category_name' => $category_name,
    'lots_list' => $lots_list,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'search_query' => $search_query,
    'current_page' => $current_page
]);

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Лоты категории "' . $category_name . '"',
    'is_auth' => $is_auth,
    'user' => $user,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);
?>