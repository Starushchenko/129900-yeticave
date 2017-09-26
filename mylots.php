<?
require_once ('vendor/autoload.php');
require_once('init.php');

$lots_categories = get_mysql_data($connect, 'SELECT * FROM categories', []);

if ($is_auth) {
    $mybets_prepared_statement = 'SELECT
        lots.image as image,
        lots.id as lot_id,
        lots.title as title,
        categories.name as category,
        lots.finish_date as finish_date,
        bets.bet_value as bet_value,
        bets.bet_date as bet_date
    FROM bets
    JOIN lots
        ON lots.id = bets.lot_id
    JOIN categories
        ON categories.id = lots.category_id
    WHERE bets.author_id = ?
    ORDER BY bets.bet_date DESC
    ';
    $bets = get_mysql_data($connect, $mybets_prepared_statement, [$user['id']]);
    
    $page_content = render_template('mylots', [
        'lots_categories' => $lots_categories,
        'bets' => $bets
    
    ]);
} else {
    $page_content = render_template('403', []);
}

// Компиляция шаблона сайта
echo render_template('layout', [
    'page_title' => 'Мои лоты',
    'is_auth' => $is_auth,
    'user' => $user,
    'page_content' => $page_content,
    'lots_categories' => $lots_categories
]);