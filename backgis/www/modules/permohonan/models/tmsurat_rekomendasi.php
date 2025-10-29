<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmsurat_rekomendasi class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class tmsurat_rekomendasi extends DataMapper {

    var $table = "tmsurat_rekomendasi";
    var $has_one = array(
        'tmpermohonan'
    );

    public function __construct() {
        parent::__construct();
    }
}

// This is the end of tmsurat_rekomendasi class
