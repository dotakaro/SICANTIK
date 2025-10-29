<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author Yana Supriatna
 * Created : 05 Aug 2010
 *
 */

class trproperty extends DataMapper {

    var $table = 'trproperty';

    var $has_one = array(
        'parentproperty' => array(
            'class' => 'trproperty',
            'other_field' => 'trproperty'
        )
    );

    var $has_many = array(
        'trproperty' => array(
            'other_field' => 'parentproperty'
        ),
        'trkoefesientarifretribusi',
        'trretribusi',
        'tmproperty_jenisperizinan',
        'trperizinan',
        'trjenis_permohonan'
        );

    public function __construct() {
        parent::__construct();
    }
    
    function cek_data1($id1, $id2){
        $sql="
            SELECT t2.*  FROM
            tmpermohonan_tmproperty_jenisperizinan as t2 
            WHERE t2.tmproperty_jenisperizinan_id = 
            (SELECT t1.id FROM trperizinan_trproperty as t1 WHERE trproperty_id = $id1 and trperizinan_id = $id2)
            ";
        return $this->db->query($sql)->num_rows();
    }
    
     function cek_data($id2, $id1) {
        $sql = "
            SELECT DISTINCT T2.id FROM trperizinan_trproperty AS T1 INNER JOIN trproperty AS T2 ON T1.trproperty_id = T2.id WHERE trperizinan_id = ".$id1." AND
            T2.id NOT IN (
            SELECT DISTINCT a.trproperty_id FROM trperizinan_trproperty AS a 
            INNER JOIN tmproperty_jenisperizinan_trproperty AS b ON a.trproperty_id = b.trproperty_id 
            WHERE a.trperizinan_id = ".$id1.") AND T2.id = ".$id2."
            ";
          $query = $this->db->query($sql);
        return $query->num_rows();
    }
    
    function is_perizinan_used_in_permohonan($id_perizinan){
        $query = "SELECT * FROM tmpermohonan_trperizinan WHERE trperizinan_id = '{$id_perizinan}'";
        $rows =  $this->db->query($query)->result_array();
        if(!empty($rows)){
            return true;
        }else{
            return false;
        }
    }
    function get_list_permohonan_property(){
        $query = "SELECT DISTINCT  B.`trproperty_id` 
                  FROM tmpermohonan_trperizinan A 
                       LEFT JOIN trperizinan_trproperty B 
                       ON A.trperizinan_id = B.trperizinan_id
                  ORDER BY B.`trproperty_id` ";
        $rows =  $this->db->query($query)->result_array();
        
        return $rows;
    }  
}

// This is the end of user class
