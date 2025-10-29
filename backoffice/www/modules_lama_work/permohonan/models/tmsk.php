<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmsurat_rekomendasi class
 *
 * @author agusnur
 * Created : 04 Sep 2010
 *
 */

class tmsk extends DataMapper {

    var $table = "tmsk";
    var $has_one = array('tmpermohonan', 'tmpegawai');

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of tmsurat_rekomendasi class
