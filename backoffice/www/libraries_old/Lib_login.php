<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Description of Lib_login class
 *
 * @author Dichi Al Faridi
 */

class Lib_login {

    public function __construct() {
        $this->CI =& get_instance();
    }

    public function check_login() {
        $is_logged_in = $this->CI->session->userdata('is_logged_in');
        if (!isset($is_logged_in) || $is_logged_in != TRUE) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}

// This is the end of Lib_login class