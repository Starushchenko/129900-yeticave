<?php

require_once('functions.php');
require_once('lotsdata.php');

$is_auth = (bool)rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';

// Валидация формы добавления лота
$rules = [
    'lot-name' => [
        'required',
    ],
    'category' => [
        'required',
    ],
    'message' => [
        'required',
    ],
    'lot-rate' => [
        'required',
        'numeric'
    ],
    'lot-step' => [
        'required',
        'numeric'
    ],
    'lot-date' => [
        'required',
    ],
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_FILES['photo'])) {
    
    
    $errors = validateForm($rules);
    $errors[] = validatePicture($_FILES['photo'], $errors);
    
    if ($_POST['category'] == 'Выберите категорию') {
        $errors[] = 'category';
    }
    
    $filled_title = $_POST['lot-name'] ?? '';
    $filled_file = $_FILES['photo'] ?? '';
    $filled_category = $_POST['category'] ?? '';
    $filled_desc = $_POST['message'] ?? '';
    $filled_price = $_POST['lot-rate'] ?? '';
    $filled_step = $_POST['lot-step'] ?? '';
    $filled_date = $_POST['lot-date'] ?? '';
    
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
    } else {
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
    }
} else {
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
}

// Компиляция шаблона сайта
echo renderTemplate('layout', [
    'page_title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories,
    'errors' => $errors
]);
?>