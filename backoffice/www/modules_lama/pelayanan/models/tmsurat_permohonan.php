<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Surat Permohonan class
 *
 * @author agusnur
 * Created : 05 Aug 2010
 */

class tmsurat_permohonan extends DataMapper {

    var $table = 'tmsurat_permohonan';
    var $has_one = array('tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}

// This is the end of pemohon_model class
