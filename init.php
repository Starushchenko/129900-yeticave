<?
require_once('mysql_helper.php');
require_once('functions.php');
require_once('session_init.php');

$connect = mysqli_connect("localhost", "root", "", "yeticave");

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
