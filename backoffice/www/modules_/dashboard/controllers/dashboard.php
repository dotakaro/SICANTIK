<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of dashboard class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Dashboard extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->session_info['page_name'] = "Home";
        $this->template->build('body', $this->session_info);
    }
}

// This is the end of dashboard class
