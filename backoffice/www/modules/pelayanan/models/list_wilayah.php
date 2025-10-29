<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Kabupaten class
 *
 * @author agusnur
 * Created : 10 Aug 2010
 *
 */

class list_wilayah extends DataMapper {
     var $table = 'trkabupaten';
    var $key = 'id';
    
    public function __construct() {
        parent::__construct();
    }
    //kabupaten
    function sql_kabupaten(){
        $sql="
            SELECT t2.* FROM
            trkabupaten_trpropinsi as t1
            JOIN trkabupaten as t2 on t1.trkabupaten_id=t2.id
            JOIN trpropinsi as t3 on t1.trpropinsi_id=t3.id
            ";
        return $sql;
    }

    function get_result_kabupaten($id){
        $sql=$this->sql_kabupaten();
        $sql.="where t3.id = $id";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //kecamatan

    function sql_kecamatan(){
        $sql="
            SELECT t2.* FROM
            trkabupaten_trkecamatan as t1
            JOIN trkecamatan as t2 on t1.trkecamatan_id= t2.id
            JOIN trkabupaten as t3 on t1.trkabupaten_id=t3.id
            ";
        return $sql;
    }

    function get_result_kecamatan($id){
        $sql=$this->sql_kecamatan();
        $sql.="where t3.id = $id";
        $query = $this->db->query($sql);
        return $query->result();
    }

    //kelurahan
    function sql_kelurahan(){
        $sql="
            SELECT t2.* FROM
            trkecamatan_trkelurahan as t1
            JOIN trkelurahan as t2 on t1.trkelurahan_id=t2.id
            JOIN trkecamatan as t3 on t1.trkecamatan_id= t3.id
            ";
        return $sql;
    }

    function get_result_kelurahan($id){
        $sql=$this->sql_kelurahan();
        $sql.="where t3.id = $id";
        $query = $this->db->query($sql);
        return $query->result();
    }

}

// This is the end of user class
