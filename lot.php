<?php
require_once ('vendor/autoload.php');
ob_start();

require_once('mysql_helper.php');
require_once('init.php');

define("DAY_SECONDS", 86400);
define("HOUR_SECONDS", 3600);

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user = $_SESSION['user'];
} else {
    $is_auth = false;
    $user = null;
}

// Подготовленные выражения для обращений в БД
$lot_prepared_statement = 'SELECT
    lots.id as lot_id,
    users.id as author_id,
    lots.title as title,
    lots.image as image,
    lots.start_price as start_price,
    categories.name as category,
    lots.description as description,
    IFNULL(MAX(bets.bet_value), lots.start_price) as lot_price,
    lots.bet_step as bet_step,
    lots.finish_date as finish_date
  FROM lots
  LEFT JOIN users
    ON users.id = lots.author_id
  LEFT JOIN categories
    ON categories.id = lots.category_id
  LEFT JOIN bets
    ON bets.lot_id = lots.id
  WHERE
    lots.id = ?
  GROUP BY lots.id
  LIMIT 1
';
$bet_prepared_statement = 'SELECT
    users.name as user_name,
    users.id as user_id,
    bets.bet_value as bet_value,
    bets.bet_date as bet_date
  FROM bets
  JOIN users
    ON users.id = bets.author_id
  WHERE
    bets.lot_id = ?
  ORDER BY
    bets.bet_date DESC
';
$winner_prepared_statement = 'SELECT
    users.name as winner,
    lots.winner_id as winner_id
    FROM lots
    LEFT JOIN users
      ON users.id = lots.winner_id
    WHERE
      lots.id = ?
      LIMIT 1';

// Получение данных из БД
$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);
$lot = get_mysql_data($connect, $lot_prepared_statement, [intval($_GET['id'])]);
$bets = get_mysql_data($connect, $bet_prepared_statement, [intval($_GET['id'])]);
$winner = get_mysql_data($connect, $winner_prepared_statement, [intval($_GET['id'])])[0]['winner'];
$bets_count = count($bets);

$bet_is_made = false;
$user_is_author = false;
foreach ($bets as $bet) {
    if ($bet['user_id'] === $user['id']) {
        $bet_is_made = true;
    }
}
if ($lot[0]['author_id'] === $user['id']) {
    $user_is_author = true;
}

// Валидация формы ставки
$form_valid = true;
$validationRules = [
    'cost' => ['rule' => 'number']
];
$form_data = [
    'cost' => ['value' => '', 'valid' => true, 'error_text' => '']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $value = get_form_data($key, $_POST, '');
        $form_data[$key]['value'] = $value;
        $form_data[$key]['valid'] = check_form_data($value, $validationRules[$key]);
        $form_valid = $form_data[$key]['valid'] ? true : false;
    }
    if (!$form_valid) {
        $form_data['cost']['error_text'] = 'Введите числовое значение ставки';
    } else {
        if ($form_data['cost']['value'] < ($lot[0]['lot_price'] + $lot[0]['bet_step'])) {
            $form_valid = false;
            $form_data['cost']['error_text'] = 'Ваша ставка меньше минимальной';
        }
    }
}

// Компиляция шаблона сайта
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($user) && isset($_GET['id']) && isset($lot) && $form_valid) {
    $inserted_bet = insert_mysql_data($connect, 'bets', [
        'bet_date' => date("Y-m-d H:i:s"),
        'bet_value' => $form_data['cost']['value'],
        'author_id' => $user['id'],
        'lot_id' => $lot[0]['lot_id']
    ]);
    
    
    if ($inserted_bet) {
        header('location: /lot.php?id=' . ($_GET['id']));
    } else {
        $page_content = render_template('502', []);
    }
    
} elseif (isset($_GET['id']) && isset($lot)) {
    $page_content = render_template('lot-detail', [
        'lot' => $lot[0],
        'bets' => $bets,
        'winner' => $winner,
        'bets_count' => $bets_count,
        'is_auth' => $is_auth,
        'lots_categories' => $lots_categories,
        'form_data' => $form_data,
        'bet_is_made' => $bet_is_made,
        'user_is_author' => $user_is_author
    ]);
} else {
    $page_content = render_template('404', []);
}


// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => $lot[0]['title'],
    'is_auth' => $is_auth,
    'user' => $user,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);


ob_end_flush() ?>