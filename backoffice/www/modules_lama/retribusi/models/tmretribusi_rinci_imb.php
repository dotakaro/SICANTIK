<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Retribusi IMB class
 *
 * @author agusnur
 * Created : 02 Nov 2010
 *
 */

class tmretribusi_rinci_imb extends DataMapper {

    var $table = 'tmretribusi_rinci_imb';
    var $has_many = array('tmpermohonan');

    public function __construct() {
        parent::__construct();
    }

}
