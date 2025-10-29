<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tr_instansi
 *
 * @author Yobi Bina Setiawan
 */
class Tr_instansi extends DataMapper {

    //put your code here
    var $table = 'settings';
    var $has_one = array();
    var $has_many = array();

    public function __construct() {
        parent::__construct();
    }

    function get_update($id) {
        return $this->db->get_where($this->table, array('id' => $id), 1)->row();
    }

    function proses_update($id, $form) {
        $this->db->where('id', $id);
        $this->db->update($this->table, $form);
    }

}

?>
