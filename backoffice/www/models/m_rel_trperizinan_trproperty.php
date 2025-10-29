<?php

class M_rel_trperizinan_trproperty extends Model {
    var $table = 'trperizinan_trpropety';
    
    function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
    }
    
    public function get_perizinan_property($perizinan_id=1){
        return $this->db->query("SELECT * FROM {$this->table} WHERE trperizinan_id = {$perizinan_id}")->result_array();
    }

}