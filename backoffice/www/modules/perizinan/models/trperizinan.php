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
class trperizinan extends DataMapper {

    var $table = 'trperizinan';
    var $has_many = array('tmpermohonan', 'tralur_perizinan', 'trproperty',
        'trsyarat_perizinan', 'tmpermohonan_trperizinan',
        'trperizinan_trproperty', 'trkoefesientarifretribusi', 'trdasar_hukum',
        'trparalel', 'user', 'trketetapan', 'trmengingat', 'trmenimbang','property_teknis_header','setting_formula_retribusi','setting_notifikasi');
    var $has_one = array('trkelompok_perizinan', 'trkabupaten',
        'trunitkerja','trretribusi','trperizinan_trretribusi','setting_tarif_item');

    public function __construct() {
        parent::__construct();
    }

    /**
     * Overriding the default function
     */
    public function delete($id = NULL) {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
        return TRUE;
    }

    function get_list(){
        $sql="SELECT trperizinan_id FROM tmpermohonan_trperizinan GROUP BY trperizinan_id";
        return $this->db->query($sql)->result();
    }
    
     /*function cek_data($id){
        $sql="SELECT COUNT(t1.id) as total FROM tmpermohonan_trperizinan as t1 WHERE t1.trperizinan_id = $id";
       return  $this->db->query($sql)->row();
    }*/
    function cek_data($syaratizin){
        $sql="SELECT COUNT(*) as total FROM tmpermohonan_trsyarat_perizinan WHERE trsyarat_perizinan_id =".$syaratizin;
       return  $this->db->query($sql)->row();
    }

}

// This is the end of massage_mdl class
