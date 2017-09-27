<?php
require_once ('vendor/autoload.php');
require_once('init.php');

error_reporting(E_ALL);

define("DAY_SECONDS", 86400);
define("HOUR_SECONDS", 3600);

$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);

// Валидация формы регистрации
$form_valid = true;
$file_valid = true;
$validationRules = [
    'email' => ['rule' => 'email'],
    'password' => ['rule' => 'not empty'],
    'name' => ['rule' => 'not empty'],
    'contacts' => ['rule' => 'not empty']
];
$form_data = [
    'email' => ['value' => '', 'valid' => true, 'error_text' => ''],
    'password' => ['value' => '', 'valid' => true],
    'name' => ['value' => '', 'valid' => true],
    'contacts' => ['value' => '', 'valid' => true]
];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        $value = get_form_data($key, $_POST, '');
        $form_data[$key]['value'] = $value;
        $form_data[$key]['valid'] = check_form_data($value, $validationRules[$key]);
    }
    $check_email = get_mysql_data($connect, 'SELECT * FROM users WHERE email = ?', [$form_data['email']['value']]);
    
    if (!$form_data['email']['valid']) {
        $form_valid = false;
        $form_data['email']['error_text'] = 'Введите корректный email';
    } elseif (!empty($check_email)) {
        $form_valid = false;
        $form_data['email']['valid'] = false;
        $form_data['email']['error_text'] = 'Пользователь с таким email уже зарегистрирован';
    };
    
    foreach ($form_data as $key => $form_data_unit) {
        if (!$form_data[$key]['valid']) {
            $form_valid = false;
            break;
        }
    }
    
    if (!empty($_FILES['avatar']['name'])) {
        if (!validate_picture($_FILES['avatar'])) {
            $form_valid = false;
            $file_valid = false;
        } else {
            $photo_tmp_name = $_FILES['avatar']['tmp_name'];
            $photo_name = $_FILES['avatar']['name'];
            move_uploaded_file($photo_tmp_name, 'img/' . $photo_name);
            $file_valid = 'img/' . $photo_name;
        }
    }
}

// Компиляция шаблона страницы
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $form_valid && $file_valid) {
    
    $inserted_user = insert_mysql_data($connect, 'users', [
        'reg_date' => date("Y-m-d"),
        'email' => $form_data['email']['value'],
        'name' => $form_data['name']['value'],
        'password_hash' => password_hash($form_data['password']['value'], PASSWORD_DEFAULT),
        'avatar_path' => ($file_valid === true) ? '' : $file_valid,
        'contacts' => $form_data['contacts']['value']
    ]);
    
    if ($inserted_user) {
        $page_content = render_template('login', [
            'lots_categories' => $lots_categories,
            'after_signup_message' => true,
            'form_data' => [
                'email' => ['value' => '', 'valid' => true, 'error_message' => 'Введите email'],
                'password' => ['value' => '', 'valid' => true, 'error_message' => 'Введите пароль']
            ]
        ]);
    } else {
        $page_content = render_template('502', []);
    }
} else {
    if ($is_auth) {
        $page_content = render_template('logged_message', ['user' => $user]);
    } else {
        $page_content = render_template('signup', [
            'lots_categories' => $lots_categories,
            'form_data' => $form_data,
            'form_valid' => $form_valid,
            'file_valid' => $file_valid
        ]);
    }
}

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Регистрация',
    'is_auth' => $is_auth,
    'user' => $user,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);