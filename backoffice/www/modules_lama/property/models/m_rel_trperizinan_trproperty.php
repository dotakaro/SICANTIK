<?php

class M_rel_trperizinan_trproperty extends Model {
    var $table = 'trperizinan_trproperty';
    
    function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
    }
  
    public function add_parent_perizinan_property($data){
        return $this->db->insert($this->table,$data);
    }
    
    public function delete_parent_perizinan_property($data){
        return $this->db->delete($this->table,$data);
    }

}