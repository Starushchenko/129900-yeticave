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
function renderTemplate($template, $template_data)
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

//
function checkDateString(string $date_string)
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
function validatePicture($picture)
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

// Функция валидации формы
function addFormToArray($post, $key, $form_data_unit)
{
    if (array_key_exists($key, $post)) {
        if ($form_data_unit['rule'] == 'not empty') {
            if ($post[$key] != '') {
                $form_data_unit['value'] = $post[$key];
                $form_data_unit['valid'] = true;
            } else {
                $form_data_unit['valid'] = false;
            }
        }
        if ($form_data_unit['rule'] == 'number') {
            if (is_numeric($post[$key]) && $post[$key] > 0) {
                $form_data_unit['value'] = $post[$key];
                $form_data_unit['valid'] = true;
            } else {
                $form_data_unit['valid'] = false;
                $form_data_unit['value'] = null;
            }
        }
        if ($form_data_unit['rule'] == 'date') {
            if (checkDateString($post[$key])) {
                $form_data_unit['value'] = $post[$key];
                $form_data_unit['valid'] = true;
            } else {
                $form_data_unit['valid'] = false;
                $form_data_unit['value'] = '';
            }
        }
        if ($form_data_unit['rule'] == 'choice') {
            if ($post[$key] != 'Выберите категорию'){
                $form_data_unit['value'] = $post[$key];
                $form_data_unit['valid'] = true;
            } else {
                $form_data_unit['valid'] = false;
                $form_data_unit['value'] = '';
            }
        }
    }
    return $form_data_unit;
}

?>