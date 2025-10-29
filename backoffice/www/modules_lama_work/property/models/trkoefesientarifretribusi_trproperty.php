<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of trperizinan_trproperty
 *
 * @author Eva
 */
class trkoefesientarifretribusi_trproperty extends DataMapper {

    var $table = 'trkoefesientarifretribusi_trproperty';
    var $has_many = array('trproperty','trsyarat_perizinan','tmpermohonan_trperizinan','trperizinan','tmpermohonan','trkoefesientarifretribusi');

      public function __construct() {
        parent::__construct();
    }

}
?>
