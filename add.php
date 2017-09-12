<?php

require_once('functions.php');
require_once('lotsdata.php');

$is_auth = (bool)rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';


// Валидация формы добавления лота
$form_valid = true;
$file_valid = true;
$form_data = [
    'lot-name' => ['value' => '', 'rule' => 'not empty', 'valid' => true],
    'category' => ['value' => '', 'rule' => 'choice', 'options' => $lots_categories, 'valid' => true],
    'message' => ['value' => '', 'rule' => 'not empty', 'valid' => true],
    'lot-rate' => ['value' => '', 'rule' => 'number', 'valid' => true],
    'lot-step' => ['value' => '', 'rule' => 'number', 'valid' => true],
    'lot-date' => ['value' => '', 'rule' => 'date', 'valid' => true],
    'img-url' => ['value' => '', 'rule' => 'upload', 'valid' => false]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $form_data[$key] = parse_form_data($_POST, $key, $form_data_unit);
        if (!$form_data[$key]['valid'] && $key != 'img-url') {
            $form_valid = false;
        }
    }
    
    if (!isset($_FILES)) {
        $form_valid = false;
        $file_valid = false;
    } else {
        if (!validate_picture($_FILES['photo'])) {
            $form_valid = false;
            $file_valid = false;
        } else {
            $file_valid = $_FILES['photo'];
            $photo_tmp_name = $_FILES['photo']['tmp_name'];
            $photo_name = $_FILES['photo']['name'];
            move_uploaded_file($photo_tmp_name, 'img/' . $photo_name);
            $form_data['img-url']['valid'] = true;
            $form_data['img-url']['value'] = 'img/' . $photo_name;
        };
    }
    
}
if ($form_data['img-url']['valid'] && $form_valid) {
    $page_content = render_template('lot-detail', [
        'bets' => $bets,
        'lots_categories' => $lots_categories,
        'lot_title' => $form_data['lot-name']['value'],
        'lot_image' => $form_data['img-url']['value'],
        'lot_category' => $form_data['category']['value'],
        'lot_desc' => $form_data['message']['value'],
        'lot_price' => $form_data['lot-rate']['value']
    ]);
} else {
    $page_content = render_template('add-lot', [
        'form_data' => $form_data,
        'file_valid' => $file_valid,
        'form_valid' => $form_valid,
        'lots_categories' => $lots_categories
    ]);
}

// Компиляция шаблона сайта
echo renderTemplate('layout', [
    'page_title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);
?>