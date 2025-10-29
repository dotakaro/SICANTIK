<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of monitoring_bi
 *
 * @author Obi
 */
class Monitoring_bi extends DataMapper {

//put your code here

    var $table = 'tmpermohonan';

    public function __construct() {
        parent::__construct();
    }

    function add_searching($aColumns, $WhereORAnd = ' WHERE ', $sSearch = "") {
        $sWhere = " ";
        if (isset($sSearch) && $sSearch != "") {
            $sWhere = " $WhereORAnd (";
            for ($i = 0; $i < count($aColumns); $i++) {
                $sWhere .= $aColumns[$i] . " LIKE '%" . $sSearch . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') ';
        }
        return $sWhere;
    }

    function get_total_perizinan($cari=NULL, $first_date=NULL, $second_date=NULL, $jenis_izin=0) {
        //$jenis_izin = $this->input->post('jenis_izin');
        $sql = $this->sql();
        $sql.=" WHERE  t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($jenis_izin != NULL) {
            $sql.=" and t3.id = $jenis_izin  ";
        }
        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }
        if ($jenis_izin == NULL) {
            return 0;
        } else {
            return $this->db->query($sql)->num_rows();
        }
    }

    function get_total_jangka_waktu($cari=NULL, $first_date=NULL, $second_date=NULL) {

        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    function get_total_kecamatan($cari=NULL, $first_date=NULL, $second_date=NULL, $kelurahan_id=NULL) {


        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if (($kelurahan_id != NULL) && ($kelurahan_id != 0)) {
            $sql.=" AND t9.id = $kelurahan_id ";
        }
        if ($cari != NULL) {

            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, "AND", $cari);
        }

        if ($kelurahan_id != 0) {
            return $this->db->query($sql)->num_rows();
        } else {
            return 0;
        }
    }

    function get_total_pemohon($cari=NULL, $first_date=NULL, $second_date=NULL, $nama=NULL) {
        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($nama != NULL) {
            $sql.=" and t5.n_pemohon LIKE  '%$nama%' ";
        }

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, "AND", $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    function get_total_perusahaan($cari=NULL, $first_date=NULL, $second_date=NULL, $nama_perusahaan) {

        $sql = $this->sql_perushaan();
        $sql.="WHERE t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($nama_perusahaan != NULL) {
            $sql.=" and t11.n_perusahaan LIKE  '%$nama_perusahaan%' ";
        }
        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t11.n_perusahaan", "t7.n_sts_permohonan", "t11.a_perusahaan", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    function get_total_Per_Bulan_Pengambilan_Izin($cari=NULL, $first_date=NULL, $second_date=NULL, $jenis_izin=NULL) {
        //$jenis_izin = $this->input->post('jenis_izin');

        $sql = $this->sql();
        $sql.=" where t7.id <> 1  ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_ambil_izin BETWEEN '$first_date' and '$second_date' ";
        }

        if ($jenis_izin != NULL) {
            $sql.=" and t3.id = $jenis_izin ";
        }

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }
        if ($jenis_izin != 0) {
            return $this->db->query($sql)->num_rows();
        } else {
            return 0;
        }
    }

    function get_total_perstatus($cari=NULL, $first_date=NULL, $second_date=NULL, $list_status=NULL) {
        // $list_status = $this->input->post('list_status');
        $sql = $this->sql();
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" where t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        if ($list_status != NULL) {
            $sql.=" and t7.id = $list_status ";
        }

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, $kond, $cari);
        }
        if ($list_status != 0) {
            return $this->db->query($sql)->num_rows();
        } else {
            return 0;
        }
    }

    function sql() {
        $sql = "
            SELECT t1.pendaftaran_id, t3.n_perizinan, t1.d_terima_berkas, t5.n_pemohon, t7.n_sts_permohonan, t5.a_pemohon, t9.n_kelurahan , t11.n_perusahaan FROM tmpermohonan as t1
            LEFT JOIN tmpermohonan_trperizinan as t2 on t1.id = t2.tmpermohonan_id
            LEFT JOIN trperizinan as t3 on t3.id = t2.trperizinan_id
            LEFT JOIN tmpemohon_tmpermohonan as t4 on t4.tmpermohonan_id = t1.id
            LEFT JOIN tmpemohon as t5 on t5.id = t4.tmpemohon_id
            LEFT JOIN tmpermohonan_trstspermohonan as t6 on t1.id = t6.tmpermohonan_id
            LEFT JOIN trstspermohonan as t7 on t7.id = t6.trstspermohonan_id
            LEFT JOIN tmpemohon_trkelurahan as t8 ON t8.tmpemohon_id= t5.id
            LEFT JOIN trkelurahan as t9 on t9.id = t8.trkelurahan_id
            LEFT JOIN tmpermohonan_tmperusahaan as t10 on t10.tmpermohonan_id = t1.id
            LEFT JOIN tmperusahaan as t11 on t11.id=t10.tmperusahaan_id
            ";
        return $sql;
    }

    function sql_perushaan() {
        $sql = "
            SELECT t1.pendaftaran_id, t3.n_perizinan, t1.d_terima_berkas, t11.n_perusahaan, t7.n_sts_permohonan, t11.a_perusahaan, t9.n_kelurahan  FROM tmpermohonan as t1
            LEFT JOIN tmpermohonan_trperizinan as t2 on t1.id = t2.tmpermohonan_id
            LEFT JOIN trperizinan as t3 on t3.id = t2.trperizinan_id
            LEFT JOIN tmpemohon_tmpermohonan as t4 on t4.tmpermohonan_id = t1.id
            LEFT JOIN tmpemohon as t5 on t5.id = t4.tmpemohon_id
            LEFT JOIN tmpermohonan_trstspermohonan as t6 on t1.id = t6.tmpermohonan_id
            LEFT JOIN trstspermohonan as t7 on t7.id = t6.trstspermohonan_id
            LEFT JOIN tmpemohon_trkelurahan as t8 ON t8.tmpemohon_id= t5.id
            LEFT JOIN trkelurahan as t9 on t9.id = t8.trkelurahan_id
            LEFT JOIN tmpermohonan_tmperusahaan as t10 on t10.tmpermohonan_id = t1.id
            LEFT JOIN tmperusahaan as t11 on t11.id=t10.tmperusahaan_id
            ";
        return $sql;
    }

}
