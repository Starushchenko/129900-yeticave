<?
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
    
    if ($delta_ts >= DAY_SECONDS) {
        print(date("d.m.y в H:i", $ts));
    } else {
        if ($delta_ts >= HOUR_SECONDS) {
            print($delta_ts / 3600 . " " . words_ending(floor($delta_ts / 3600), ["час", "часа", "часов"]) . " назад");
        } else {
            print($delta_ts / 60 . " " . words_ending(floor($delta_ts / 60), ["минута", "минуты", "минут"]) . " назад");
        }
    }
}

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
    
    $mime = ['image/jpeg'];
    
    if (is_uploaded_file($f_tmp_name) && in_array($f_type, $mime) && $f_size < 500000 && !$f_error) {
        $result = true;
    } else {
        $result = false;
    }
    
    return $result;
}

// Функция поиска элемента в ассоциативном массиве
function searchInArray($needle, $array, $array_key) {
    $result = null;
    foreach ($array as $elem => $value) {
        if ($array[$elem][$array_key] && $array[$elem][$array_key] == $needle) {
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
    }
}

?>