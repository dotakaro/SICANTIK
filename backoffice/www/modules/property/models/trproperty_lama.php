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
    
    function cek_data($id1, $id2){
        $sql="
            SELECT t2.* as total FROM
            tmpermohonan_tmproperty_jenisperizinan as t2 
            WHERE t2.tmproperty_jenisperizinan_id = 
            (SELECT t1.id FROM trperizinan_trproperty as t1 WHERE trproperty_id = $id1 and trperizinan_id = $id2)
            ";
        return $this->db->query($sql)->num_rows();
    }
    

}

// This is the end of user class
