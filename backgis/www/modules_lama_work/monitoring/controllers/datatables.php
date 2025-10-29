<?php

/**
 * Description of datatables
 *
 * @author alfaridi
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Datatables extends WRC_AdminCont {
    /*
     * The Object
     */

    var $obj;

    /*
     * Variable for generating JSON.
     */
    var $iTotalRecords;
    var $iTotalDisplayRecords;

    /*
     * Variable that taken form input.
     */
    var $iDisplayStart;
    var $iDisplayLength;
    var $iSortingCols;
    var $sSearch;
    var $sEcho;

    public function __construct() {
        parent::__construct();
    }

    public function list_izin() {

        $obj = new tmpermohonan();
        $obj->start_cache();

        /*
         * Custom criteria
         */

        $jenis_izin = NULL;
        $first_date = NULL;
        $second_date = NULL;
        $first_date_taken = NULL;
        $second_date_taken = NULL;
        $kelurahan_id = NULL;
        $list_status = NULL;
        $nama = NULL;
        $ambil_izin = NULL;
        $nama_perusahaan = NULL;
        $list_state = NULL;
        $namalike = NULL;

        $jenis_izin = $this->input->post('jenis_izin');
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');
        $first_date_taken = $this->input->post('first_date_taken');
        $second_date_taken = $this->input->post('second_date_taken');
        $kelurahan_id = $this->input->post('kelurahan_id');
        $list_status = $this->input->post('list_status');
        $nama = $this->input->post('nama');
        $ambil_izin = $this->input->post('ambil_izin');
        $nama_perusahaan = $this->input->post('nama_perusahaan');
        $list_state = $this->input->post('list_state');
        $namalike = $this->input->post('sSearch');

        if ($jenis_izin != NULL) {
            $obj->where_related('trperizinan', 'id', $jenis_izin);
        }

        if ($namalike != NULL) {
            $obj->like('pendaftaran_id', $namalike);
            $obj->or_ilike('d_terima_berkas', $namalike);
            $obj->or_ilike_related('trperizinan', 'n_perizinan', $namalike);
            $obj->or_ilike_related('tmpemohon', 'n_pemohon', $namalike);
            $obj->or_ilike_related("trstspermohonan", "n_sts_permohonan", $namalike);
            $obj->or_ilike_related("tmpemohon", "a_pemohon", $namalike);
        }

        if ($kelurahan_id != NULL) {
            $obj->where_related('tmpemohon/trkelurahan', 'id', $kelurahan_id);
        }

        if ($list_status != NULL) {
            $obj->where_related('trstspermohonan', 'id', $list_status);
        }

        if ($first_date != NULL && $second_date != NULL) {
            $obj->where('d_terima_berkas >= ', $first_date);
            $obj->where('d_terima_berkas <= ', $second_date);
        }

        if ($first_date_taken != NULL && $second_date_taken != NULL) {
            $obj->where('d_ambil_izin >= ', $first_date_taken);
            $obj->where('d_ambil_izin <= ', $second_date_taken);
        }

        if ($nama != NULL) {
            $obj->ilike_related('tmpemohon', 'n_pemohon', $nama, 'like');
        }

        if ($nama_perusahaan != NULL) {
            // $obj->ilike_related('tmperusahaan', 'n_perusahaan', $nama_perusahaan, 'after');
            $obj->ilike_related('tmperusahaan', 'n_perusahaan', $nama_perusahaan, 'like');
        }

        if ($list_state != NULL) {
            switch ($list_state) {
                case '0' :
                    $obj->where_related('tmbap', 'c_penetapan', $list_state);
                    break;
                case '1' :
                    $obj->where_related('tmbap', 'c_penetapan', $list_state);
                    $obj->where('d_berlaku_izin <= ', $this->lib_date->get_date_now());
                    break;
                case '2' :
                    $obj->where('d_berlaku_izin > ', $this->lib_date->get_date_now());
                    break;
            }
        }

        $this->iTotalRecords = $obj->count();
        $this->sEcho = $this->input->post('sEcho');
        for ($i = 0; $i < 2; $i++) {
            /**
             * Filtering
             */
            /**
             * Ordering
             */
//            if ($this->input->post("iSortCol_0") != null && $this->input->post("iSortCol_0") != "") {
//                for ($i = 0; $i < intval($this->input->post("iSortingCols")); $i++) {
//                    $obj->order_by($columns[intval($this->input->post("iSortCol_" . $i))], $this->input->post("sSortDir_" . $i));
//                }
//            }

            if ($i === 0) {
                $this->iTotalDisplayRecords = $obj->count();
            } else if ($i === 1) {
                if ($this->input->post("iDisplayStart") && $this->input->post("iDisplayLength") != "-1") {
                    $this->iDisplayStart = $this->input->post("iDisplayStart");
                    $this->iDisplayLength = $this->input->post("iDisplayLength");

                    $obj->limit($this->iDisplayLength, $this->iDisplayStart);
                } else {
                    $this->iDisplayLength = $this->input->post("iDisplayLength");

                    if (empty($this->iDisplayLength)) {
                        $this->iDisplayLength = 10;
                        $obj->limit($this->iDisplayLength);
                    }
                    else
                        $obj->limit($this->iDisplayLength);
                }

                $obj->stop_cache();
                $obj->get();

                $aaData = array();

                $i = $this->iDisplayStart;

                foreach ($obj as $list) {
                    $i++;

                    $action = NULL;

                    $img_edit = array(
                        'src' => base_url() . 'assets/images/icon/tick.png',
                        'alt' => 'Pilih',
                        'title' => 'Pilih',
                        'border' => '0',
                    );

                    $list->tmpemohon->get();
                    $list->tmperusahaan->get();
                    $list->tmpemohon->trkelurahan->get();
                    $list->trperizinan->get();
                    $list->trstspermohonan->get();
                    $list->tmbap->get();

                    if ($nama_perusahaan != NULL) {
                        $aaData[] = array(
                            $i,
                            $list->pendaftaran_id,
                            $list->trperizinan->n_perizinan,
                            $this->lib_date->mysql_to_human($list->d_terima_berkas),
                            $list->tmperusahaan->n_perusahaan,
                            $list->trstspermohonan->n_sts_permohonan,
                            $list->tmpemohon->a_pemohon,
                            $list->tmpemohon->trkelurahan->n_kelurahan,
                        );
                    } else if ($list_state != NULL) {

                        $state = NULL;

                        if ($list->d_berlaku_izin > $this->lib_date->get_date_now()) {
                            $state = "Kadaluarsa";
                        } else if ($list->tmbap->c_penetapan === '1') {
                            $state = "Sudah Jadi";
                        } else if ($list->tmbap->c_penetapan === '0') {
                            $state = "Belum Jadi";
                        }

                        $aaData[] = array(
                            $i,
                            $list->pendaftaran_id,
                            $list->trperizinan->n_perizinan,
                            $this->lib_date->mysql_to_human($list->d_terima_berkas),
                            $list->tmpemohon->n_pemohon,
                            $state,
                            $list->tmpemohon->a_pemohon,
                            $list->tmpemohon->trkelurahan->n_kelurahan,
                        );
                    } else {
                        $aaData[] = array(
                            $i,
                            $list->pendaftaran_id,
                            $list->trperizinan->n_perizinan,
                            $this->lib_date->mysql_to_human($list->d_terima_berkas),
                            $list->tmpemohon->n_pemohon,
                            $list->trstspermohonan->n_sts_permohonan,
                            $list->tmpemohon->a_pemohon,
                            $list->tmpemohon->trkelurahan->n_kelurahan,
                        );
                    }
                }

                $sOutput = array
                    (
                    "sEcho" => intval($this->sEcho),
                    "iTotalRecords" => $this->iTotalRecords,
                    "iTotalDisplayRecords" => $this->iTotalDisplayRecords,
                    "aaData" => $aaData
                );

                echo json_encode($sOutput);
            }
        }
    }

    //======================================= baru koding
    function list_kecamatan() {
        $kelurahan_id = $this->input->post('kelurahan_id');
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');
        $jenis_izin = $this->input->post('jenis_izin');
        if (empty($kelurahan_id)) {
            $kelurahan_id = 0;
        }
        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_data($iDisplayLength, $iDisplayStart, $cari, $kelurahan_id, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_pemohon,
                    $list->n_sts_permohonan,
                    $list->a_pemohon,
                    $list->n_kelurahan,
                );
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_kecamatan($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_kecamatan($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_data($limit=NULL, $start=NULL, $cari=NULL, $kelurahan_id=NULL, $first_date=NULL, $second_date=NULL) {
        if ($start == NULL || $start == 0)
            $start = 0;
        if ($limit == NULL || $limit == 0)
            $limit = 10;

        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";
        if ($kelurahan_id != NULL || $kelurahan_id != 0) {
            $sql.="AND t9.id = $kelurahan_id ";
        }
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        $sql.="LIMIT $start,$limit";
        if ($kelurahan_id != NULL) {
            return $this->db->query($sql)->result();
        } else {
            return array();
        }
    }

    function get_total_kecamatan($cari=NULL, $first_date=NULL, $second_date=NULL) {
        $kelurahan_id = $this->input->post('kelurahan_id');

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

    //========================================================================================
	
	/*
	* @ModifiedAuthor Indra
	* Modified: 27-04-2013
	* @ModifiedComment Penambahan nilai retribusi untuk ditampilkan di grid
	*/
    function list_Monitoring_Per_Perizinan() {
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');

        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_data_Per_Perizinan($iDisplayLength, $iDisplayStart, $cari, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_pemohon,
                    $list->n_sts_permohonan,
                    $list->a_pemohon,
                    $list->n_kelurahan,
					$this->_get_biaya_retribusi($list->id)
                );
				
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_perizinan($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_perizinan($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_data_Per_Perizinan($limit=NULL, $start=NULL, $cari=NULL, $first_date=NULL, $second_date=NULL) {
        $jenis_izin = $this->input->post('jenis_izin');
        $sql = $this->sql();

        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" where t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($jenis_izin != NULL) {
            $sql.=" and t3.id = $jenis_izin and t7.id <> 1 ";
        }
        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        $sql.="LIMIT $start,$limit";
        return $this->db->query($sql)->result();
    }

    function get_total_perizinan($cari=NULL, $first_date=NULL, $second_date=NULL) {
        $jenis_izin = $this->input->post('jenis_izin');
        $sql = $this->sql();
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" where t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($jenis_izin != NULL) {
            $sql.=" and t3.id = $jenis_izin and t7.id <> 1 ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    //===========================================================================================
    function list_Monitoring_Per_Jangka_Waktu() {
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');

        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_Monitoring_Per_Jangka_Waktu($iDisplayLength, $iDisplayStart, $cari, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_pemohon,
                    $list->n_sts_permohonan,
                    $list->a_pemohon,
                    $list->n_kelurahan,
                );
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_jangka_waktu($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_jangka_waktu($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_Monitoring_Per_Jangka_Waktu($limit=NULL, $start=NULL, $cari=NULL, $first_date=NULL, $second_date=NULL) {

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

        $sql.="LIMIT $start,$limit";
        return $this->db->query($sql)->result();
    }

    function get_total_jangka_waktu($cari=NULL, $first_date=NULL, $second_date=NULL) {

        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    //==========================================================================================

    function list_Monitoring_Per_Status() {
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');

        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_Monitoring_Per_Status($iDisplayLength, $iDisplayStart, $cari, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_pemohon,
                    $list->n_sts_permohonan,
                    $list->a_pemohon,
                    $list->n_kelurahan,
                );
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_perstatus($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_perstatus($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_Monitoring_Per_Status($limit=NULL, $start=NULL, $cari=NULL, $first_date=NULL, $second_date=NULL) {
        $list_status = $this->input->post('list_status');

        $sql = $this->sql();

        if ($list_status == 1) {
            $sql = $this->sql_penftran_sementara();
        }
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" where t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($list_status != 1) {
            if ($list_status != NULL) {
                $sql.=" and t7.id = $list_status ";
            }
        }

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        $sql.="LIMIT $start,$limit";
        return $this->db->query($sql)->result();
    }

    function get_total_perstatus($cari=NULL, $first_date=NULL, $second_date=NULL) {
        $list_status = $this->input->post('list_status');
        $sql = $this->sql();
        if ($list_status == 1) {
            $sql = $this->sql_penftran_sementara();
        }
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" where t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        if ($list_status != 1) {
            if ($list_status != NULL) {
                $sql.=" and t7.id = $list_status ";
            }
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    //==========================================================================================
    function list_Monitoring_Per_Nama_Pemohon() {
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');

        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_Monitoring_Per_Nama_Pemohon($iDisplayLength, $iDisplayStart, $cari, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_pemohon,
                    $list->n_sts_permohonan,
                    $list->a_pemohon,
                    $list->n_kelurahan,
                );
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_pemohon($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_pemohon($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_Monitoring_Per_Nama_Pemohon($limit=NULL, $start=NULL, $cari=NULL, $first_date=NULL, $second_date=NULL) {
        $nama = $this->input->post('nama');

        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";

        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        if ($nama != NULL) {
            $sql.=" and t5.n_pemohon LIKE  '%$nama%' ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        $sql.="LIMIT $start,$limit";
        return $this->db->query($sql)->result();
    }

    function get_total_pemohon($cari=NULL, $first_date=NULL, $second_date=NULL) {
        $nama = $this->input->post('nama');
        $sql = $this->sql();
        $sql.=" where t7.id <> 1 ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($nama != NULL) {
            $sql.=" and t5.n_pemohon LIKE  '%$nama%' ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t5.n_pemohon", "t7.n_sts_permohonan", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, "AND", $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    //=========================================================================================
    function list_Monitoring_Per_Nama_Perusahaan() {
        $first_date = $this->input->post('first_date');
        $second_date = $this->input->post('second_date');

        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_Monitoring_Per_Nama_Perusahaan($iDisplayLength, $iDisplayStart, $cari, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_perusahaan,
                    $list->n_sts_permohonan,
                    $list->a_perusahaan,
                    $list->n_kelurahan,
                );
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_perusahaan($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_perusahaan($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_Monitoring_Per_Nama_Perusahaan($limit=NULL, $start=NULL, $cari=NULL, $first_date=NULL, $second_date=NULL) {
        $nama = $this->input->post('nama_perusahaan');

        $sql = $this->sql_perushaan();
        $sql.=" WHERE t7.id <> 1 AND t11.n_perusahaan <> '-'";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }

        if ($nama != NULL) {
            $sql.=" and t11.n_perusahaan LIKE  '%$nama%' ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t11.n_perusahaan", "t7.n_sts_permohonan", "t11.a_perusahaan", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        $sql.="LIMIT $start,$limit";
        
        //file_put_contents('get_list_Monitoring_Per_Nama_Perusahaan_sql', $sql);
        return $this->db->query($sql)->result();
    }

    function get_total_perusahaan($cari=NULL, $first_date=NULL, $second_date=NULL) {
        $nama = $this->input->post('nama_perusahaan');
        $sql = $this->sql_perushaan();
        $sql.=" WHERE t7.id <> 1 AND t11.n_perusahaan <> '-'";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_terima_berkas BETWEEN '$first_date' and '$second_date' ";
        }
        if ($nama != NULL) {
            $sql.=" and t11.n_perusahaan LIKE  '%$nama%' ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan", "t1.d_terima_berkas", "t11.n_perusahaan", "t7.n_sts_permohonan", "t11.a_perusahaan", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    //=======================================================================================
    function list_Monitoring_Per_Bulan_Pengambilan_Izin() {
        $first_date = $this->input->post('first_date_taken');
        $second_date = $this->input->post('second_date_taken');

        if (empty($first_date)) {
            $first_date = 0;
        }
        if (empty($second_date)) {
            $second_date = 0;
        }
        //============def
        $iDisplayLength = $this->input->post('iDisplayLength');
        $iDisplayStart = $this->input->post('iDisplayStart');
        $cari = $this->input->post('sSearch');
        $aaData = array();
        $i = 0;
        if (!empty($iDisplayStart)) {
            $i = $iDisplayStart;
        }
        $obj = $this->get_list_Monitoring_Per_Bulan_Pengambilan_Izin($iDisplayLength, $iDisplayStart, $cari, $first_date, $second_date);

        if ($obj) {
            foreach ($obj as $list) {
                $i++;
                $aaData[] = array(
                    $i,
                    $list->pendaftaran_id,
                    $list->n_perizinan,
                    $this->lib_date->mysql_to_human($list->d_terima_berkas),
                    $list->n_pemohon,
                   $list->no_surat,
                    $this->lib_date->mysql_to_human($list->tgl_surat),
                    $list->a_pemohon,
                    $list->n_kelurahan,
                );
            }
        }
        $sOutput = array
            (
            "sEcho" => $this->input->post('sEcho'),
            "iTotalRecords" => $this->get_total_Per_Bulan_Pengambilan_Izin($cari, $first_date, $second_date),
            "iTotalDisplayRecords" => $this->get_total_Per_Bulan_Pengambilan_Izin($cari, $first_date, $second_date),
            "aaData" => $aaData
        );
        echo json_encode($sOutput);
    }

    function get_list_Monitoring_Per_Bulan_Pengambilan_Izin($limit=NULL, $start=NULL, $cari=NULL, $first_date=NULL, $second_date=NULL) {
        $jenis_izin = $this->input->post('jenis_izin');

        $sql = $this->sql();
        $sql.=" where t7.id <> 1  ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.="AND  t1.d_ambil_izin BETWEEN '$first_date' and '$second_date' ";
        }

        if ($jenis_izin != NULL) {
            $sql.=" and t3.id = $jenis_izin ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

         if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan","t5.n_pemohon", "t1.d_terima_berkas", "t13.no_surat", "t13.tgl_surat", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        $sql.="LIMIT $start,$limit";
        return $this->db->query($sql)->result();
    }

    function get_total_Per_Bulan_Pengambilan_Izin($cari=NULL, $first_date=NULL, $second_date=NULL) {
        $jenis_izin = $this->input->post('jenis_izin');

        $sql = $this->sql();
        $sql.=" where t7.id <> 1  ";
        if (($first_date != NULL) && ($first_date != 0) && ($second_date != NULL) && ($second_date != 0)) {
            $sql.=" AND t1.d_ambil_izin BETWEEN '$first_date' and '$second_date' ";
        }

        if ($jenis_izin != NULL) {
            $sql.=" and t3.id = $jenis_izin ";
        }

        $sql.=" AND t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";

        if ($cari != NULL) {
            $colom_cari = array(
                "t1.pendaftaran_id", "t3.n_perizinan","t5.n_pemohon", "t1.d_terima_berkas", "t13.no_surat", "t13.tgl_surat", "t5.a_pemohon", "t9.n_kelurahan"
            );
            $sql.=$this->lib_query->add_searching($colom_cari, 'AND', $cari);
        }

        return $this->db->query($sql)->num_rows();
    }

    //=========================================================================================
    function get_total() {
        $sql = $this->sql();
        $sql.=" WHERE t1.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})";
        $sql.=" AND t3.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})";
        return $this->db->query($sql)->num_rows();
    }
	
	/**
	* @ModifiedAuthor Indra
	* Modified: 27-04-2013
	* @ModifiedComment Penambahan kolom id permohonan pada hasil query
	*/
    function sql() {
        $sql = "
            SELECT t1.id, t1.pendaftaran_id, t3.n_perizinan, t1.d_terima_berkas,t13.no_surat,t13.tgl_surat, t5.n_pemohon, t7.n_sts_permohonan, t5.a_pemohon, t9.n_kelurahan , t11.n_perusahaan FROM tmpermohonan as t1
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
            LEFT JOIN tmpermohonan_tmsk as t12 on t12.tmpermohonan_id=t1.id
            LEFT JOIN tmsk as t13 on t13.id = t12.tmsk_id
            ";

        return $sql;
    }

    function sql_penftran_sementara() {
        $sql = "
            SELECT t1.pendaftaran_id, t3.n_perizinan, t1.d_terima_berkas, t5.n_pemohon, t7.n_sts_permohonan, t5.a_pemohon, t9.n_kelurahan , t11.n_perusahaan FROM tmpermohonan as t1
            LEFT JOIN tmpermohonan_trperizinan as t2 on t1.id = t2.tmpermohonan_id
            LEFT JOIN trperizinan as t3 on t3.id = t2.trperizinan_id
            JOIN tmpemohon_sementara_tmpermohonan as t4 on t4.tmpermohonan_id = t1.id
            LEFT JOIN tmpemohon_sementara as t5 on t5.id = t4.tmpemohon_sementara_id
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
	
	/*
	* @Author Indra
	* Created : 27-04-2013
	* Fungsi untuk mendapatkan biaya retribusi dari suatu permohonan
	* @param integer $permohonan_id Id permohonan yang ingin diperoleh retribusinya
	* @return float $nilai_ret Nilai retribusi untuk suatu permohonan
	*/
	private function _get_biaya_retribusi($permohonan_id){
		$this->permohonan = new tmpermohonan();
		$this->permohonan->where('id', $permohonan_id)->get();
        $this->permohonan->trperizinan->get();
        $this->permohonan->tmbap->get();
		
        $this->permohonan->trperizinan->trretribusi->get();
		$retribusi = $this->permohonan->tmbap->nilai_bap_awal;
        $keringanan = $this->permohonan->tmkeringananretribusi->get();
        if ($keringanan->id)
        {
            $nilai_ret1 = ($keringanan->v_prosentase_retribusi * 0.01) * $retribusi;
            $nilai_ret = $retribusi-$nilai_ret1;
        }else
        {
            $nilai_ret = $retribusi;
        }
		return $nilai_ret;
	}

}

