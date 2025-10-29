<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of massage_mdl class
 *
 * @author  Yogi Cahyana
 * @since
 *
 */

class trparalel extends DataMapper {

    var $table = 'trparalel';
    var $has_many = array('trperizinan');

    public function __construct() {
        parent::__construct();
    }
    
    function cek_data($id){
        $sql="SELECT COUNT(t1.id) as total FROM trparalel_trperizinan as t1 WHERE t1.trparalel_id = $id";
       return  $this->db->query($sql)->row();
    }

}

// This is the end of massage_mdl class
