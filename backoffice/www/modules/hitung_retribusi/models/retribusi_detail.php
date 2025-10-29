<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model class for report component module
 * @author Indra Halim
 * @version 1.0
 */
class retribusi_detail extends DataMapper {
    var $table = 'retribusi_detail';
    var $has_one = array('retribusi', 'setting_tarif_item');

    public function __construct() {
        parent::__construct();
    }
}