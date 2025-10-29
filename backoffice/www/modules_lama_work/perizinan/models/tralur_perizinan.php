<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yana Supriatna
 * Created : 05 Aug 2010
 *
 */

class tralur_perizinan extends DataMapper {

    var $table = 'tralur_perizinan';
    var $has_one = array('trperizinan','user_auths');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
