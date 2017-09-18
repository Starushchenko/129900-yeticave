<?php

require_once('functions.php');
require_once('lotsdata.php');

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name'];
} else {
    $is_auth = false;
}

// устанавливаем часовой пояс в Московское время
date_default_timezone_set('Europe/Moscow');

// Рассчет времени до окончания текущих суток
$lot_time_remaining = calt_time_to_tomorrow();


// Компиляция шаблона страницы
$page_content = render_template('index',
    ['lots_categories' => $lots_categories, 'lots_list' => $lots_list, 'lot_time_remaining' => $lot_time_remaining]);

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'lots_categories' => $lots_categories,
    'page_content' => $page_content
]);
?>
