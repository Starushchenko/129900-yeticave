<?
require_once "vendor/autoload.php";
require_once "init.php";

$lots_query = ' SELECT id, title
    FROM lots
    WHERE finish_date <= NOW()
    AND winner_id is NULL
';

$lots = get_mysql_data($connect, $lots_query, []);
if ($lots) {
    $bets_query = '
            SELECT
                bets.author_id as author_id,
                users.name as user_name,
                users.email as user_email,
                bets.lot_id as lot_id,
                bets.bet_value as bet_value
            FROM bets
            LEFT JOIN users
                ON bets.author_id = users.id
            WHERE lot_id = ?
            ORDER BY bet_value DESC
            LIMIT 1
';
    
    $update_winner_id_query = 'UPDATE lots
        SET winner_id = ?
        WHERE id = ?;
    ';
    
    foreach ($lots as $value) {
        $winner_bets = get_mysql_data($connect, $bets_query, [$value['id']]);
        if (!empty($winner_bets)) {
            
            $winner_bet = array_shift ($winner_bets);
            if (execute_mysql_query($connect, $update_winner_id_query, [
                $winner_bet['author_id'],
                $winner_bet['lot_id']
            ])) {
                $email_content = render_template('email', [
                    'winner' => $winner_bet['user_name'],
                    'lot_id' => $winner_bet['lot_id'],
                    'lot_title' => $value['title']
                ]);
                $message = (new Swift_Message())
                    ->setSubject('Ваша ставка победила')
                    ->setFrom('doingsdone@mail.ru')
                    ->setTo($winner_bet['user_email'], $winner_bet['user_name'])
                    ->setBody($email_content, 'text/html');
                $mailer->send($message);
            }
        }
    }
}