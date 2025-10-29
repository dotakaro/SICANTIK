<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tmproperty_klasifikasi
 *
 * @author agusnur
 * Created : 01 Nov 2010
 * 
 */

class tmproperty_klasifikasi extends DataMapper {

    var $table = 'tmproperty_klasifikasi';

    var $has_many = array('tmpermohonan', 'trkoefesientarifretribusi');

      public function __construct() {
        parent::__construct();
    }

}
?>
