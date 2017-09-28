<?php
ini_set("display_errors", 1);
error_reporting(E_ALL);
require_once('mysql_helper.php');
require_once('functions.php');
require_once('session_init.php');
error_reporting(E_ALL);

$connect = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($connect, 'utf8');

if (!$connect) {
    $connect_error = mysqli_connect_error();
    
    // Компиляция шаблона страницы
    $page_content = render_template('error', ['error' => $connect_error]);
    
    // Компиляция шаблона сайта
    echo render_template('layout', [
        'page_title' => 'Ошибка подключения',
        'is_auth' => '',
        'user_name' => '',
        'lots_categories' => [],
        'page_content' => $page_content
    ]);
    exit();
}

$transport = (new Swift_SmtpTransport('smtp.mail.ru', 465,
    'ssl'))
    ->setUsername('doingsdone@mail.ru')
    ->setPassword('rds7BgcL');

$mailer = new Swift_Mailer($transport);
