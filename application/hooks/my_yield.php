<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php

class My_yield {

    function doYield() {

        global $OUT;

        $CI = & get_instance();




        $output = $CI->output->get_output();
        if (!isset($CI->layout)) {

            $CI->layout = "default";
        }
       
        if (isset($CI->layout)) {
            if (!preg_match('/(.+).php$/', $CI->layout)) {
                $CI->layout .= '.php';
            }

            $l = explode("_", $CI->layout);
            $requested = APPPATH . 'views/layouts/' . $CI->layout;

            if (!in_array('admin', $l)) {
                $default = APPPATH . 'views/layouts/default.php';
            } 
            else {
                $default = APPPATH . 'views/layouts/default_admin.php';
            }

            if (file_exists($requested)) {
                $layout = $CI->load->file($requested, true);
                $view = str_replace("{my_yield}", $output, $layout);
            } 
            else {
                $layout = $CI->load->file($default, true);
                $view = str_replace("{my_yield}", $output, $layout);
            }
        } 
        else {
            $view = $output;
        }

        $OUT->_display($view);
    }

}

?>