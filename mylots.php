<?

require_once('functions.php');
require_once('lotsdata.php');

// Рассчет времени до окончания текущих суток
$time_remaining = calt_time_to_tomorrow();

session_start();
if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_name = $_SESSION['user']['name'];
} else {
    $is_auth = false;
}

// Если есть ставки, берем их из cookie
$user_bets = [];
if (isset($_COOKIE['bets'])) {
    $user_bets = json_decode($_COOKIE['bets'], true);
}

$page_content = render_template('mylots', [
    'lots_list' => $lots_list,
    'lots_categories' => $lots_categories,
    'time_remaining' => $time_remaining,
    'bets' => $user_bets
    
]);

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Мои лоты',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);

?>