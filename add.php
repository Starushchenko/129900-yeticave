<?php

require_once('functions.php');
require_once('lotsdata.php');

$is_auth = (bool)rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';




 /*
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
    
    if (!validatePicture($_FILES['photo'])) {
        $errors[] = 'photo';
    } else {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_name = $_FILES['photo']['name'];
        $photo_url = 'img/' . $photo_name;
        move_uploaded_file($photo_tmp_name, $photo_url);
    };
    
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
}     */

//----------------------------------------

$form_valid = true;
$form_data = [
    'lot_name' => ['value' => '', 'rule' => 'not empty', 'valid' => true],
    'category' => ['value' => '', 'rule' => 'choice', 'valid' => true],
    'message' => ['value' => '', 'rule' => 'not empty', 'valid' => true],
    'lot_rate' => ['value' => '', 'rule' => 'number', 'valid' => true],
    'lot_step' => ['value' => '', 'rule' => 'number', 'valid' => true],
    'lot_date' => ['value' => '', 'rule' => 'date', 'valid' => true],
    'img_url' => ['value' => '', 'rule' => 'upload', 'valid' => false]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $form_data[$key] = addFormToArray($_POST, $key, $form_data_unit);
        if (!$form_data[$key]['valid'] && $key != 'img_url') {
            $form_valid = false;
        }
    }
    if (!isset($_FILES) || !$form_valid) {
        $form_valid = false;
    } elseif (!array_key_exists('photo', $_FILES)) {
        $form_valid = false;
    } elseif ($_FILES['photo']['name'] != '') {
        $photo_tmp_name = $_FILES['photo']['tmp_name'];
        $photo_name = $_FILES['photo']['name'];
        $photo_url = 'img/' . $photo_name;
        move_uploaded_file($photo_tmp_name, $photo_url);
        $form_data['img_url']['valid'] = true;
    } else {
        $form_valid = false;
    }
}
if ($form_data['img_url']['valid']) {
    $page_content = renderTemplate(
        'lot-detail',
        [   'lots_categories' => $lots_categories,
            'lot_name' => $form_data['lot_name']['value'],
            'img_url' => $form_data['img_url']['value'],
            'category' => $form_data['category']['value'],
            'message' => $form_data['message']['value'],
            'lot_rate' => $form_data['lot_rate']['value']
        ]
    );
} else {
    $page_content = renderTemplate('add-lot', [
        'form_data' => $form_data,
        'form_valid' => $form_valid,
        'lots_categories' => $lots_categories]);
}



//----------------------------------------------------------------

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