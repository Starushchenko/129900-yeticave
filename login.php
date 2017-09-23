<?php

require_once('lotsdata.php');
require_once('userdata.php');
require_once('mysql_helper.php');
require_once('init.php');

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name'];
} else {
    $is_auth = false;
}

// Валидация формы авторизации
$form_valid = true;
$validationRules = [
    'email' => ['rule' => 'email'],
    'password' => ['rule' => 'not empty']
];
$form_data = [
    'email' => ['value' => '', 'valid' => true, 'error_message' => 'Введите email'],
    'password' => ['value' => '', 'valid' => true, 'error_message' => 'Введите пароль']
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($form_data as $key => $form_data_unit) {
        
        $value = get_form_data($key, $_POST, '');
        $form_data[$key]['value'] = $value;
        $form_data[$key]['valid'] = check_form_data($value, $validationRules[$key]);
        $form_valid = $form_data[$key]['valid'] ? true : false;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $form_valid) {
    if ($user = searchInArray($form_data['email']['value'], $users, 'email')) {
        if (password_verify($form_data['password']['value'], $user['password'])) {
            $_SESSION['user'] = $user;
            header("Location: /index.php");
        } else {
            $form_valid = false;
            $form_data['password']['valid'] = false;
            $form_data['password']['error_message'] = 'Вы ввели неверный пароль';
            unset($_SESSION['user']);
        }
    } else {
        $form_valid = false;
        $form_data['email']['valid'] = false;
        $form_data['email']['error_message'] = 'Пользователь с таким email не зарегистрирован';
        unset($_SESSION['user']);
    }
}

// Компиляция шаблона страницы

if ($is_auth) {
    $page_content = render_template('logged_message', [
        'user_name' => $user_name,
    ]);
} else {
    $page_content = render_template('login', [
        'lots_categories' => $lots_categories,
        'form_data' => $form_data
    ]);
}

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'YetiCave - Авторизация',
    'is_auth' => $is_auth,
    'user_avatar' => $user_avatar,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);

?>