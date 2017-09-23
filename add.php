<?php

require_once('lotsdata.php');
require_once('mysql_helper.php');
require_once('init.php');

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name'];
} else {
    $is_auth = false;
}

// Валидация формы добавления лота
$form_valid = true;
$file_valid = true;
$validationRules = [
    'lot-name' => ['rule' => 'not empty'],
    'category' => ['rule' => 'choice', 'options' => $lots_categories],
    'message' => ['rule' => 'not empty'],
    'lot-rate' => ['rule' => 'number'],
    'lot-step' => ['rule' => 'number'],
    'lot-date' => ['rule' => 'date']
];
$form_data = [
    'lot-name' => ['value' => '', 'valid' => true],
    'category' => ['value' => '', 'valid' => true],
    'message' => ['value' => '', 'valid' => true],
    'lot-rate' => ['value' => '', 'valid' => true],
    'lot-step' => ['value' => '', 'valid' => true],
    'lot-date' => ['value' => '', 'valid' => true]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $value = get_form_data($key, $_POST, '');
        
        $form_data[$key]['value'] = $value;
        $form_data[$key]['valid'] = check_form_data($value, $validationRules[$key]);
        $form_valid = $form_data[$key]['valid'] ? true : false;
    }
    
    if (!isset($_FILES) || !validate_picture($_FILES['photo'])) {
        $form_valid = false;
        $file_valid = false;
    } else {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_name = $_FILES['photo']['name'];
        move_uploaded_file($photo_tmp_name, 'img/' . $photo_name);
        $file_valid = 'img/' . $photo_name;
    };
    
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $form_valid && $file_valid) {
    $page_content = render_template('lot-detail', [
        'bets' => $bets,
        'user_bets' => [],
        'is_auth' => $is_auth,
        'lots_categories' => $lots_categories,
        'lot_title' => $form_data['lot-name']['value'],
        'lot_image' => $file_valid,
        'lot_category' => $form_data['category']['value'],
        'lot_desc' => $form_data['message']['value'],
        'lot_price' => $form_data['lot-rate']['value']
    ]);
} else if ($is_auth) {
    $page_content = render_template('add-lot', [
        'form_data' => $form_data,
        'file_valid' => $file_valid,
        'form_valid' => $form_valid,
        'lots_categories' => $lots_categories
    ]);
} else {
    $page_content = render_template('403', []);
}

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);
?>