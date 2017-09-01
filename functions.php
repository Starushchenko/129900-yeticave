<?
// Функция правильного окончания слов "минута" и "час" в зависимости от числа
function words_ending(int $number, array $words_array) {
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
function calc_time_ago($ts) {
    $delta_ts = strtotime('now') - $ts;
    
    if ($delta_ts >= DAY_SECONDS) {
        print(date("d.m.y в H:i", $ts));
    } else if ($delta_ts >= HOUR_SECONDS) {
        print($delta_ts / 3600 . " " . words_ending(floor($delta_ts / 3600), ["час", "часа", "часов"]) . " назад");
    } else {
        print($delta_ts / 60 . " " . words_ending(floor($delta_ts / 60), ["минута", "минуты", "минут"]) . " назад");
    }
}

// Функция шаблонизации
function renderTemplate($template, $template_data) {
    if (file_exists('templates/' . $template . '.php')) {
        ob_start('ob_gzhandler');
        extract($template_data);
        require_once('templates/' . $template . '.php');
        
        return ob_get_clean();
    } else {
        return '';
    }
}
?>