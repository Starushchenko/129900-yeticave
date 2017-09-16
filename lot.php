<?php
ob_start();

require_once('functions.php');
require_once('lotsdata.php');

define("DAY_SECONDS", 86400);
define("HOUR_SECONDS", 3600);

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name'];
} else {
    $is_auth = false;
}

// Если есть ставки, берем их из cookie
$user_bets = [];
if (isset($_COOKIE['bets'])) {
    $user_bets = json_decode($_COOKIE['bets'], true);
}

// Валидация формы ставки
$form_valid = true;
$validationRules = [
    'cost' => ['rule' => 'number']
];
$form_data = [
    'cost' => ['value' => '', 'valid' => true]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $value = get_form_data($key, $_POST, '');
        $form_data[$key]['value'] = $value;
        $form_data[$key]['valid'] = check_form_data($value, $validationRules[$key]);
        $form_valid = $form_data[$key]['valid'] ? true : false;
    }
}

// Компиляция шаблона сайта
$bet_is_made = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_GET['id']) && array_key_exists($_GET['id'], $lots_list) && $form_valid) {
    $user_bets[$lots_list[$_GET['id']]['title']] = [
        'bet_index' => $_GET['id'],
        'bet_author' => $user_name,
        'bet_value' => $form_data['cost']['value'],
        'bet_timestamp' => strtotime('now')
    ];
    $bet_is_made = true;
    $user_bets_encoded = json_encode($user_bets);
    header('location: /mylots.php');
    setcookie('bets', $user_bets_encoded, time() + DAY_SECONDS);
    
} else {
    if (isset($_GET['id']) && array_key_exists($_GET['id'], $lots_list)) {
        $page_content = render_template('lot-detail', [
            'bets' => $bets,
            'user_bets' => $user_bets,
            'is_auth' => $is_auth,
            'lots_categories' => $lots_categories,
            'form_data' => $form_data,
            'bet_is_made' => $bet_is_made,
            'lot_index' => $_GET['id'],
            'lot_title' => $lots_list[$_GET['id']]['title'],
            'lot_image' => $lots_list[$_GET['id']]['src'],
            'lot_category' => $lots_list[$_GET['id']]['category'],
            'lot_desc' => $lots_list[$_GET['id']]['desc'],
            'lot_price' => $lots_list[$_GET['id']]['price']
        ]);
    } else {
        $page_content = render_template('404', []);
    }
}

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => $lots_list[$_GET['id']]['title'],
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);


ob_end_flush() ?>