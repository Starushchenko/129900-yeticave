<?
define("IMG_TYPE_JPG", "image/jpeg");
define("IMG_TYPE_PNG", "image/png");

// Функция правильного окончания слов "минута" и "час" в зависимости от числа
function words_ending(int $number, array $words_array)
{
    switch (($number >= 20) ? $number % 10 : $number) {
        case 1:
            $result = array_key_exists(0, $words_array) ? $words_array[0] : 'n';
            break;
        case 2:
        case 3:
        case 4:
            $result = array_key_exists(1, $words_array) ? $words_array[1] : 'n';
            break;
        default:
            $result = array_key_exists(2, $words_array) ? $words_array[2] : 'n';
    }
    
    return $result;
}

// Функция рассчета времени в относительном формате
function calc_time_ago($ts)
{
    $delta_ts = strtotime('now') - $ts;
    
    if ($delta_ts >= 86400) {
        return date("d.m.y в H:i", $ts);
    } else {
        if ($delta_ts >= 3600) {
            return (floor($delta_ts / 3600) . " " . words_ending(floor($delta_ts / 3600),
                    ["час", "часа", "часов"]) . " назад");
        } else {
            return (floor($delta_ts / 60) . " " . words_ending(floor($delta_ts / 60),
                    ["минута", "минуты", "минут"]) . " назад");
        }
    }
}

// Функция рассчета промежутка времени между двумя ts в формате HH:MM
function calc_time_to_tomorrow()
{
    $tomorrow = strtotime('tomorrow midnight');
    $now = strtotime('now');
    $difference = ($tomorrow - $now);
    
    return str_pad(floor($difference / 3600), 2, '0', STR_PAD_LEFT) . ":" . str_pad(($difference / 60) % 60, 2, '0',
            STR_PAD_LEFT);
}

;


// Функция шаблонизации
function render_template($template, $template_data)
{
    if (file_exists('templates/' . $template . '.php')) {
        ob_start('ob_gzhandler');
        extract($template_data);
        require_once('templates/' . $template . '.php');
        
        return ob_get_clean();
    } else {
        return '';
    }
}

// Функция проверки даты в формате DD.MM.YYYY
function check_date_string(string $date_string)
{
    if (preg_match('#^[0-3](?(?<=3)[01]|\d)\.[01](?(?<=1)[0-2]|\d)\.20[1-3](?(?<=3)[0-4]|\d)$#', $date_string)) {
        if (date('d.m.Y', strtotime($date_string)) == $date_string) {
            if (strtotime($date_string) > strtotime('now')) {
                return true;
            }
        }
    }
    
    return false;
}

// Функция валидации загруженного изображения
function validate_picture($picture)
{
    $f_type = $picture['type'];
    $f_size = $picture['size'];
    $f_tmp_name = $picture['tmp_name'];
    $f_error = $picture['error'];
    
    $possibleMimeTypes = [IMG_TYPE_JPG, IMG_TYPE_PNG];
    
    if (is_uploaded_file($f_tmp_name) && in_array($f_type, $possibleMimeTypes) && $f_size < 500000 && !$f_error) {
        $result = true;
    } else {
        $result = false;
    }
    
    return $result;
}

// Функция поиска элемента в ассоциативном массиве
function searchInArray($needle, $array, $array_key)
{
    $result = null;
    foreach ($array as $elem => $value) {
        if (isset($array[$elem][$array_key]) && $array[$elem][$array_key] == $needle) {
            $result = $array[$elem];
            break;
        }
    }
    
    return $result;
}

// Функции валидации формы: получение данных и проверка-сравнение на правила в массиве
function get_form_data($key, $post, $default)
{
    return array_key_exists($key, $post) ? $post[$key] : $default;
}

function check_form_data($value, $validationRules)
{
    if ($validationRules['rule'] == 'not empty') {
        return ($value != '');
    }
    if ($validationRules['rule'] == 'number') {
        return (is_numeric($value) && $value > 0);
    }
    if ($validationRules['rule'] == 'date') {
        return (check_date_string($value));
    }
    if ($validationRules['rule'] == 'choice') {
        return ($value != 'Выберите категорию' && in_array($value, $validationRules['options']));
    }
    if ($validationRules['rule'] == 'email') {
        return (filter_var($value, FILTER_VALIDATE_EMAIL));
    } else {
        return false;
    }
}

// Функция получения данных из БД
function get_mysql_data($connect, $sql_query, array $query_values)
{
    $data = [];
    
    $prepared_statement = db_get_prepare_stmt($connect, $sql_query, $query_values);
    if ($prepared_statement) {
        if (mysqli_stmt_execute($prepared_statement)) {
            $result = mysqli_stmt_get_result($prepared_statement);
            if ($result) {
                $data = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }
        }
    }
    
    return $data;
}

// Функция вставки данных в БД
function insert_mysql_data($connect, $db_table, array $inserted_values)
{
    $db_columns_names = implode(', ',
        array_keys($inserted_values)); // строка названий столбцов таблицы, в которые записываются значения
    $placeholders = implode(', ', array_fill(0, count($inserted_values), '?')); // передаваемые значения
    $values = array_values($inserted_values); // строка плейсхолдеров для подготовленного выражения
    
    $sql_query = 'INSERT INTO ' . $db_table . ' (' . $db_columns_names . ')' . ' VALUES (' . $placeholders . ')';
    $prepared_statement = db_get_prepare_stmt($connect, $sql_query, $values);
    if ($prepared_statement) {
        if (mysqli_stmt_execute($prepared_statement)) {
            $last_added_id = mysqli_stmt_insert_id($prepared_statement);
            
            return $last_added_id;
        }
    }
    
    return false;
}

// Функция выполнения произвольного запроса (кроме SELECT И INSERT)
function execute_mysql_query($connect, $sql_query, array $query_values)
{
    $prepared_statement = db_get_prepare_stmt($connect, $sql_query, $query_values);
    if ($prepared_statement) {
        if (mysqli_stmt_execute($prepared_statement)) {
            return true;
        }
    }
    
    return false;
}

?>