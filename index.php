<?php

require_once('functions.php');
require_once('lotsdata.php');

$is_auth = (bool)rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

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

// Массив категорий
$lots_categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

// Компиляция шаблона страницы
$page_content = renderTemplate('index',
    ['lots_categories' => $lots_categories, 'lots_list' => $lots_list, 'lot_time_remaining' => $lot_time_remaining]);

// Компиляция шаблона сайта
echo renderTemplate('layout', [
    'page_title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'page_content' => $page_content
]);
?>
