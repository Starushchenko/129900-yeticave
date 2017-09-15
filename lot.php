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

// Компиляция шаблона сайта
if (isset($_GET['id']) and array_key_exists($_GET['id'], $lots_list)) {
    $page_content = render_template('lot-detail', [
        'bets' => $bets,
        'is_auth' => $is_auth,
        'lots_categories' => $lots_categories,
        'lot_title' => $lots_list[$_GET['id']]['title'],
        'lot_image' => $lots_list[$_GET['id']]['src'],
        'lot_category' => $lots_list[$_GET['id']]['category'],
        'lot_desc' => $lots_list[$_GET['id']]['desc'],
        'lot_price' => $lots_list[$_GET['id']]['price']
    ]);
}  else {
    $page_content = render_template('404', []);
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