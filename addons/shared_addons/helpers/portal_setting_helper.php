<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * function to get Portal Theme Setting
 *
 * @author Indra Halim
 *
 */
if ( ! function_exists('get_portal_theme')) {

    function get_portal_theme($field = '')
    {
        $setting_value = '';
        $CI = &get_instance();
        $CI->load->model('portal_theme/portal_theme_m');
        $portal_theme_m = new portal_theme_m();
        $id = 1;//sesuai record di database
        $data = $portal_theme_m->get($id);
        if (!empty($data) && isset($data->$field)) {
            $setting_value = $data->$field;
        }
        return $setting_value;
    }
}