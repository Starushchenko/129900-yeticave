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
                lots.title as lot_title,
                bets.bet_value as bet_value
            FROM bets
            JOIN users
                ON bets.author_id = users.id
            JOIN lots
                ON bets.lot_id = lots.id
            WHERE lot_id = ?
            ORDER BY bet_value DESC
            LIMIT 1
';
    
    $update_winner_id_query = 'UPDATE lots
        SET winner_id = ?
        WHERE id = ?;
    ';
    
    
    foreach ($lots as $index => $value) {
        $winner_bets = get_mysql_data($connect, $bets_query, [$value['id']]);
        if ($winner_bets) {
            foreach ($winner_bets as $value) {
                $winner_bet = $value;
            }
            $is_query_exec = execute_mysql_query($connect, $update_winner_id_query, [
                $winner_bet['author_id'],
                $winner_bet['lot_id']
            ]);
            if ($is_query_exec) {
                $email_content = render_template('email', [
                    'winner' => $winner_bet['user_name'],
                    'lot_id' => $winner_bet['lot_id'],
                    'lot_title' => $winner_bet['lot_title']
                ]);
                $transport = (new Swift_SmtpTransport('smtp.mail.ru', 465,
                    'ssl'))
                    ->setUsername('doingsdone@mail.ru')
                    ->setPassword('rds7BgcL');
                $message = (new Swift_Message())
                    ->setSubject('Ваша ставка победила')
                    ->setFrom('doingsdone@mail.ru')
                    ->setTo($winner_bet['user_email'], $winner_bet['user_name'])
                    ->setBody($email_content, 'text/html');
                $mailer = new Swift_Mailer($transport);
                $mailer->send($message);
            }
        }
    }
}