<?
function compile_template($template, $template_data) {
    if (file_exists('templates/' . $template)) {
        ob_start('ob_gzhandler');
        extract($template_data);
        require_once('templates/' . $template);
        
        return ob_get_clean();
    } else {
        return '';
    }
}
?>