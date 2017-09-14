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

// записать в эту переменную оставшееся время в этом формате (ЧЧ:ММ)
$lot_time_remaining = "00:00";

// временная метка для полночи следующего дня
$tomorrow = strtotime('tomorrow midnight');

// временная метка для настоящего времени
$now = strtotime('now');

// значение оставшегося времени в секундах
$difference = ($tomorrow - $now);
$lot_time_remaining = str_pad(floor($difference / 3600), 2, '0', STR_PAD_LEFT) . ":" . str_pad(($difference / 60) % 60,
        2, '0', STR_PAD_LEFT);


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
