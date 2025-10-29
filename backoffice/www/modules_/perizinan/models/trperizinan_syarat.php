<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Permohonan & Syarat class
 *
 * @author Agus Nurwahid
 * Created : 13 Aug 2010
 *
 */

class trperizinan_syarat extends DataMapper {

    var $table = 'trperizinan_trsyarat_perizinan';

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of user class
