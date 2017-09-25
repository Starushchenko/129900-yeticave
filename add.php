<?php

require_once('init.php');

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user = $_SESSION['user'];
} else {
    $is_auth = false;
}

$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);
$lots_categories_names = [];
foreach ($lots_categories as $key => $value) {
    $lots_categories_names[] .= $lots_categories[$key]['name'];
}

// Валидация формы добавления лота
$form_valid = true;
$file_valid = true;
$validationRules = [
    'lot-name' => ['rule' => 'not empty'],
    'category' => ['rule' => 'choice', 'options' => $lots_categories_names],
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
    'lot-date' => ['value' => '', 'valid' => true, 'error_text' => '']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $value = get_form_data($key, $_POST, '');
        
        $form_data[$key]['value'] = $value;
        $form_data[$key]['valid'] = check_form_data($value, $validationRules[$key]);
    }
    
    if (!$form_data['lot-date']['value']) {
        $form_data['lot-date']['error_text'] = 'Введите дату в формате ДД.ММ.ГГГГ';
    } else if (strtotime(str_replace('.', '-', $form_data['lot-date']['value']).date('H:i:s',time())) < (strtotime('+1 day'))) {
        $form_valid = false;
        $form_data['lot-date']['error_text'] = 'Конец аукциона не может быть ранее 24 часов от начала';
    }
    
    foreach ($form_data as $key => $form_data_unit) {
        if (!$form_data[$key]['valid']) {
            $form_valid = false;
            break;
        }
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
    $added_lot = insert_mysql_data($connect, 'lots', [
        'title' => $form_data['lot-name']['value'],
        'description' => $form_data['message']['value'],
        'create_date' => date("Y-m-d H:i:s"),
        'finish_date' => date('Y-m-d H:i:s', strtotime(str_replace('.', '-', $form_data['lot-date']['value']).date('H:i:s',time()))),
        'image' => $file_valid,
        'start_price' => $form_data['lot-rate']['value'],
        'bet_step' => $form_data['lot-step']['value'],
        'fav_count' => 0,
        'author_id' => $user['id'],
        'category_id' => searchInArray($form_data['category']['value'], $lots_categories, 'name')['id']
    ]);
    
    if($added_lot) {
        $lot_prepared_statement = 'SELECT
            lots.id as lot_id,
            users.id as author_id,
            lots.title as title,
            lots.image as image,
            lots.start_price as start_price,
            categories.name as category,
            lots.description as description,
            IFNULL(MAX(bets.bet_value), lots.start_price) as lot_price,
            lots.bet_step as bet_step,
            lots.finish_date as finish_date
          FROM lots
          LEFT JOIN users
            ON users.id = lots.author_id
          LEFT JOIN categories
            ON categories.id = lots.category_id
          LEFT JOIN bets
            ON bets.lot_id = lots.id
          WHERE
            lots.id = ?
          GROUP BY lots.id
          LIMIT 1
        ';
    
        $lot = get_mysql_data($connect, $lot_prepared_statement, [$added_lot]);
        
        $page_content = render_template('lot-detail', [
            'lot' => $lot[0],
            'bets' => [],
            'bets_count' => 0,
            'user_bets' => [],
            'is_auth' => $is_auth,
            'lots_categories' => $lots_categories,
            'form_data' => $form_data,
            'bet_is_made' => false,
        ]);
    } else {
        $page_content = render_template('502', []);
    }
    
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
    'user' => $user,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);
?>