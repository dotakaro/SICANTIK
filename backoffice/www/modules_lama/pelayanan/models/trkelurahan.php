<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Kelurahan class
 *
 * @author agusnur
 * Created : 10 Aug 2010
 *
 */
class trkelurahan extends DataMapper {

    var $table = 'trkelurahan';
    var $has_one = array('trkecamatan');
    var $has_many = array('tmpemohon', 'tmperusahaan', 'tmpemohon_sementara', 'tmperusahaan_sementara');

    public function __construct() {
        parent::__construct();
    }

    function cek_data($id) {
        $sql = "
            SELECT
            t1.id
            FROM
            tmpemohon_trkelurahan as t1
            WHERE t1.trkelurahan_id = $id
            ";
        return $this->db->query($sql)->num_rows();
    }

    function cek_data_kel() {
        $sql = "
            select a.id,a.n_kelurahan,b.id as id_kelurahan, b.n_kecamatan,d.id as id_kabupaten,d.n_kabupaten,f.id as id_propinsi,f.n_propinsi from trkelurahan as a
            inner join trkecamatan_trkelurahan as c on c.trkelurahan_id=a.id
            inner join trkecamatan as b on c.trkecamatan_id=b.id
            inner join trkabupaten_trkecamatan as e on e.trkecamatan_id = b.id
            inner join trkabupaten as d on e.trkabupaten_id=d.id
            inner join trkabupaten_trpropinsi as g on g.trkabupaten_id=d.id
            inner join trpropinsi as f on g.trpropinsi_id = f.id
            ";
        return $sql;
    }

    function cek_data_all_wilyah() {
        $sql = $this->cek_data_kel();
        $sql.="where a.id in (SELECT
            t1.trkelurahan_id
            FROM
            tmpemohon_trkelurahan as t1 
            )";
       return $this->db->query($sql)->result();
    }

}

// This is the end of user class
