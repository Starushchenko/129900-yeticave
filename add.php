<?php

require_once('functions.php');
require_once('lotsdata.php');

$is_auth = (bool)rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

// ставки пользователей, которыми надо заполнить таблицу
$bets = [
    ['name' => 'Иван', 'price' => 11500, 'ts' => strtotime('-' . rand(1, 50) . ' minute')],
    ['name' => 'Константин', 'price' => 11000, 'ts' => strtotime('-' . rand(1, 18) . ' hour')],
    ['name' => 'Евгений', 'price' => 10500, 'ts' => strtotime('-' . rand(25, 50) . ' hour')],
    ['name' => 'Семён', 'price' => 10000, 'ts' => strtotime('last week')]
];

// Массив категорий
$lots_categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

// Валидация формы добавления лота
$number_inputs = ['lot-rate', 'lot-step'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($_POST as $key => $value) {
        if ($value == '') {
            $errors[] = $key;
        }
        
        if (in_array($key, $number_inputs) && !is_numeric($value)) {
            $errors[] = $key;
        }
    }
    
    if ($_POST['category'] == 'Выберите категорию') {
        $errors[] = 'category';
    }
    
    if (isset($_FILES['photo'])) {
        $photo_name = $_FILES['photo']['name'];
        $photo_url = 'img/' . $photo_name;
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo_url);
    } else {
        $errors[] = 'photo';
    }
    
    $filled_title = $_POST['lot-name'] ?? '';
    $filled_file = $_FILES['photo'] ?? '';
    $filled_category = $_POST['category'] ?? '';
    $filled_desc = $_POST['message'] ?? '';
    $filled_price = $_POST['lot-rate'] ?? '';
    $filled_step = $_POST['lot-step'] ?? '';
    $filled_date = $_POST['lot-date'] ?? '';
    
    $page_content = renderTemplate('add-lot', [
        'errors' => $errors,
        'lots_categories' => $lots_categories,
        'lot_name' => $filled_title,
        'lot_category' => $filled_category,
        'lot_desc' => $filled_desc,
        'lot_file' => $filled_file,
        'lot_rate' => $filled_price,
        'lot_step' => $filled_step,
        'lot_date' => $filled_date,
    ]);
    
    if (!count($errors)) {
        $page_content = renderTemplate('lot-detail', [
            'lot_title' => $_POST['lot-name'],
            'lot_image' => $photo_url,
            'lot_category' => $_POST['category'],
            'lot_price' => $_POST['lot-rate'],
            'lot_desc' => $_POST['message'],
            'lots_categories' => $lots_categories,
            'bets' => $bets
        ]);
    }
} else {
    $page_content = renderTemplate('add-lot', [
        'errors' => $errors,
        'lots_categories' => $lots_categories,
    ]);
}

// Компиляция шаблона сайта
echo renderTemplate('layout', [
    'page_title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'errors' => $errors
]);
?>