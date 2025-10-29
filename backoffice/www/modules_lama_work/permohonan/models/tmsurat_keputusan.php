<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmsurat_keputusan class
 *
 * @author agusnur
 * Created : 19 Dec 2010
 *
 */

class tmsurat_keputusan extends DataMapper {

    var $table = "tmsurat_keputusan";
    var $has_one = array('tmpermohonan', 'tmpegawai');

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of tmsurat_rekomendasi class
