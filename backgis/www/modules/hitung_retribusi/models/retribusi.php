<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model class for report component module
 * @author Indra Halim
 * @version 1.0
 */
class retribusi extends DataMapper {
    var $table = 'retribusi';
    var $has_one = array('tmpermohonan');
    var $has_many = array('retribusi_detail');

    public function __construct() {
        parent::__construct();
    }
}