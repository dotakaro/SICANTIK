<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tmproperty_prasarana
 *
 * @author agusnur
 * Created : 01 Nov 2010
 * 
 */

class tmproperty_prasarana extends DataMapper {

    var $table = 'tmproperty_prasarana';

    var $has_many = array('tmpermohonan', 'trkoefesientarifretribusi');

      public function __construct() {
        parent::__construct();
    }

}
?>
