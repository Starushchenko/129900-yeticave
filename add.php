<?php

require_once('functions.php');
require_once('lotsdata.php');

$is_auth = (bool)rand(0, 1);

$user_name = 'Константин';
$user_avatar = 'img/user.jpg';


// Валидация формы добавления лота

$form_valid = true;
$form_data = [
    'lot-name' => ['value' => '', 'rule' => 'not empty', 'valid' => true],
    'category' => ['value' => '', 'rule' => 'choice', 'valid' => true],
    'message' => ['value' => '', 'rule' => 'not empty', 'valid' => true],
    'lot-rate' => ['value' => '', 'rule' => 'number', 'valid' => true],
    'lot-step' => ['value' => '', 'rule' => 'number', 'valid' => true],
    'lot-date' => ['value' => '', 'rule' => 'date', 'valid' => true],
    'img-url' => ['value' => '', 'rule' => 'upload', 'valid' => false]
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
        if (!validatePicture($_FILES['photo'])) {
            $form_valid = false;
        } else {
            $photo_tmp_name = $_FILES['photo']['tmp_name'];
            $photo_name = $_FILES['photo']['name'];
            $photo_url = '/img/' . $photo_name;
            $form_data['img_url']['value'] = $photo_url;
            move_uploaded_file($_FILES['photo']['tmp_name'], $photo_url);
            $form_data['img_url']['valid'] = true;
        };
    }
}
if ($form_data['img_url']['valid']) {
    $page_content = renderTemplate(
        'lot-detail',
        [   'lots_categories' => $lots_categories,
            'lot_name' => $form_data['lot-name']['value'],
            'img_url' => $form_data['img-url']['value'],
            'category' => $form_data['category']['value'],
            'message' => $form_data['message']['value'],
            'lot_rate' => $form_data['lot-rate']['value']
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