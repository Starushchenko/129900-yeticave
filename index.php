<?php
require_once ('vendor/autoload.php');
require_once('init.php');
require_once('getwinner.php');

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// Получение данных из БД
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
ORDER BY lots.create_date DESC
LIMIT 3 OFFSET ?';

$current_page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($current_page < 1) ? 0 : (int)($current_page - 1) * 3;

$lots_count = get_mysql_data($connect, 'SELECT COUNT(lots.id) AS lots_count FROM lots WHERE lots.finish_date > NOW()',
    [])[0]["lots_count"];
$pages_count = ceil($lots_count / 3);
$pages = range(1, $pages_count);

// Получение лотов
$lots_list = (array)get_mysql_data($connect, $lots_sql_query, [$offset]);

// Компиляция шаблона страницы
$page_content = render_template('index', [
    'lots_categories' => $lots_categories,
    'lots_list' => $lots_list,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'current_page' => $current_page
]);

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user' => $user,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);
