<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of hitung retribusi class
 * Class untuk Hitung Retribusi
 * @author  Indra Halim
 * @since   1.0
 *
 */
class Rekomendasi extends WRC_AdminCont
{

    private $_status_rekomendasi = 5;

    public function __construct()
    {
        parent::__construct();

        $this->load->model('survey/tim_teknis');
        $this->tim_teknis = new tim_teknis();
    }

    /*private function _check_auth(){
        $enabled = FALSE;//enable hak akses
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '11' || $list_auth->id_role === '12') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }
    }*/

    public function index()
    {
//        $this->_check_auth();
        $sALL = 0;
        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if ($tgla && $tglb) {
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        } else {
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $username = new user();
        $username->where('username', $this->session->userdata('username'))->get();

        $current_unitkerja = $this->__get_current_unitkerja();
        if ($this->__is_administrator()) {
            //$query_filter_user=" ";
            //$query_join_perizinan_user= " ";
            $query_filter_unit = "";
//            $query_filter_unit = " AND L.trunitkerja_id ={$current_unitkerja->id} ";
        } else {
            //$query_filter_user=" AND P.user_id = '" . $username->id . "' ";
            //$query_join_perizinan_user= " INNER JOIN trperizinan_user AS P ON  P.trperizinan_id = C.id ";
            $query_filter_unit = " AND L.trunitkerja_id ={$current_unitkerja->id} ";
        }

        $status_rekomendasi = $this->_status_rekomendasi;//Lihat table trstspermohonan
        if ($sALL == 1) {
            /*$query = "SELECT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                        C.id idizin, C.n_perizinan, E.n_pemohon,
                        G.id idjenis, G.n_permohonan, A.c_izin_selesai, L.id AS tim_teknis_id, L.status_tinjauan,
                        I.c_pesan, I.id as bap_id, M.n_unitkerja, L.rekomendasi
                    FROM tmpermohonan as A
                        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                        INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                        INNER JOIN tmbap as I ON H.tmbap_id = I.id".
                        //$query_join_perizinan_user.
                        " INNER JOIN tmpermohonan_trtanggal_survey J ON J.tmpermohonan_id = A.id
                        INNER JOIN trtanggal_survey K ON K.id = J.trtanggal_survey_id
                        INNER JOIN tim_teknis L ON (L.trtanggal_survey_id=K.id AND L.id=I.tim_teknis_id)
                        INNER JOIN trunitkerja M ON M.id=L.trunitkerja_id ".
                    "WHERE
                        I.c_pesan IS NOT NULL
                        AND A.c_pendaftaran = 1
                        AND A.c_izin_dicabut = 0
                        AND A.c_izin_selesai = 1
                        AND L.status_tinjauan = 1".
                        //$query_filter_user.
                        "AND A.d_terima_berkas between '$tgla' and '$tglb'
                        $query_filter_unit
                        AND (SELECT COUNT(*) FROM tmpermohonan_trstspermohonan WHERE tmpermohonan_id = A.id AND trstspermohonan_id={$status_rekomendasi})>0
                    ORDER BY A.id DESC";*/
            $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                        C.id idizin, C.n_perizinan, E.n_pemohon,
                        G.id idjenis, G.n_permohonan, A.c_izin_selesai, L.id AS tim_teknis_id, L.status_tinjauan,
                        /*I.c_pesan, I.id as bap_id, */M.n_unitkerja, L.rekomendasi
                    FROM tmpermohonan as A
                        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                        INNER JOIN tmpermohonan_trtanggal_survey J ON J.tmpermohonan_id = A.id
                        INNER JOIN trtanggal_survey K ON K.id = J.trtanggal_survey_id
                        INNER JOIN tim_teknis L ON (L.trtanggal_survey_id=K.id)
                        INNER JOIN trunitkerja M ON M.id=L.trunitkerja_id
                        INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                        LEFT JOIN tmbap as I ON (H.tmbap_id = I.id AND I.c_pesan IS NOT NULL AND I.tim_teknis_id=L.id)
                    WHERE
                        A.c_pendaftaran = 1
                        AND A.c_izin_dicabut = 0
                        AND A.c_izin_selesai = 1
                        AND A.d_terima_berkas between '$tgla' and '$tglb'
                        $query_filter_unit
                        AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
                        AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_rekomendasi})>0
                    ORDER BY A.id DESC";

        } else {
            /*$query = "SELECT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                        C.id idizin, C.n_perizinan, E.n_pemohon,
                        G.id idjenis, G.n_permohonan, A.c_izin_selesai, L.id AS tim_teknis_id, L.status_tinjauan,
                        I.c_pesan, I.id as bap_id, M.n_unitkerja, L.rekomendasi
                    FROM tmpermohonan as A
                        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                        INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                        INNER JOIN tmbap as I ON H.tmbap_id = I.id".
                        //$query_join_perizinan_user.
                        " INNER JOIN tmpermohonan_trtanggal_survey J ON J.tmpermohonan_id = A.id
                        INNER JOIN trtanggal_survey K ON K.id = J.trtanggal_survey_id
                        INNER JOIN tim_teknis L ON (L.trtanggal_survey_id=K.id AND L.id=I.tim_teknis_id)
                        INNER JOIN trunitkerja M ON M.id=L.trunitkerja_id ".
                    "WHERE
                        I.c_pesan IS NOT NULL
                        AND A.c_pendaftaran = 1
                        AND A.c_izin_dicabut = 0
                        AND A.c_izin_selesai = 0
                        AND L.status_tinjauan = 1 ".
                        //$query_filter_user.
                        "AND A.d_terima_berkas between '$tgla' and '$tglb'
                        $query_filter_unit
                        AND (SELECT COUNT(*) FROM tmpermohonan_trstspermohonan WHERE tmpermohonan_id = A.id AND trstspermohonan_id={$status_rekomendasi})>0
                    ORDER BY A.id DESC";*/
            $query = "SELECT DISTINCT A.id, A.pendaftaran_id, A.c_tinjauan, A.d_terima_berkas, A.d_survey,
                        A.d_perubahan, A.d_perpanjangan, A.d_daftarulang,
                        C.id idizin, C.n_perizinan, E.n_pemohon,
                        G.id idjenis, G.n_permohonan, A.c_izin_selesai, L.id AS tim_teknis_id, L.status_tinjauan,
                        /*I.c_pesan, I.id as bap_id, */M.n_unitkerja, L.rekomendasi
                    FROM tmpermohonan as A
                        INNER JOIN tmpermohonan_trperizinan as B ON B.tmpermohonan_id = A.id
                        INNER JOIN trperizinan as C ON B.trperizinan_id = C.id
                        INNER JOIN tmpemohon_tmpermohonan as D ON D.tmpermohonan_id = A.id
                        INNER JOIN tmpemohon as E ON D.tmpemohon_id = E.id
                        INNER JOIN tmpermohonan_trjenis_permohonan as F ON F.tmpermohonan_id = A.id
                        INNER JOIN trjenis_permohonan as G ON F.trjenis_permohonan_id = G.id
                        INNER JOIN tmpermohonan_trtanggal_survey J ON J.tmpermohonan_id = A.id
                        INNER JOIN trtanggal_survey K ON K.id = J.trtanggal_survey_id
                        INNER JOIN tim_teknis L ON (L.trtanggal_survey_id=K.id)
                        INNER JOIN trunitkerja M ON M.id=L.trunitkerja_id
                        INNER JOIN tmbap_tmpermohonan as H ON H.tmpermohonan_id = A.id
                        LEFT JOIN tmbap as I ON (H.tmbap_id = I.id AND I.c_pesan IS NOT NULL AND I.tim_teknis_id=L.id)
                WHERE
                        A.c_pendaftaran = 1
                        AND A.c_izin_dicabut = 0
                        AND A.c_izin_selesai = 0
                        AND A.d_terima_berkas between '$tgla' and '$tglb'
                        $query_filter_unit
                        /*AND A.trunitkerja_id IN (SELECT trunitkerja_id FROM trunitkerja_user WHERE trunitkerja_user.user_id = {$this->session->userdata('id_auth')})
                        AND C.id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})
                        */AND (SELECT COUNT(*) FROM tmpermohonan_tmtrackingperizinan pt
                            INNER JOIN tmtrackingperizinan_trstspermohonan ts ON pt.tmtrackingperizinan_id = ts.tmtrackingperizinan_id
                            WHERE pt.tmpermohonan_id = A.id AND ts.trstspermohonan_id = {$status_rekomendasi})>0
                    ORDER BY A.id DESC";
        }

        $data['list'] = $query;
        $data['mapping_rekomendasi'] = array(
            'Direkomendasikan' => 'Direkomendasikan',
            'Tidak Direkomendasikan' => 'Tidak Direkomendasikan',
            'Direkomendasikan dengan Catatan' => 'Direkomendasikan dengan Catatan',
            'Perlu Pembahasan Lebih Lanjut' => 'Perlu Pembahasan Lebih Lanjut'
        );

        $this->load->vars($data);
        $js = "
                $(document).ready(function() {
                        oTable = $('#rekomendasi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });
                $(function() {
                    $(\".monbulan\").datepicker({
                        changeMonth: true,
                        changeYear: true,
                        dateFormat: 'yy-mm-dd',
                        closeText: 'X'
                    });
                });
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Rekomendasi Tim Teknis";
        $this->template->build('index', $this->session_info);
    }

    public function edit($tim_teknis_id = null)
    {
        if ($tim_teknis_id != '') {
//            $this->_check_auth();

            //Ambil data permohonan
            $data_rekomendasi = $this->tim_teknis->get_by_id($tim_teknis_id);

            $this->load->model('survey/trtanggal_survey');
            $trtanggal_survey_id = $data_rekomendasi->trtanggal_survey_id;
            $trtanggal_survey = new trtanggal_survey();
            $data_tanggal_survey = $trtanggal_survey
                ->include_related('tmpermohonan', array('pendaftaran_id'))
                ->include_related('tmpermohonan/tmpemohon', array('n_pemohon'))
                ->include_related('tmpermohonan/trperizinan', array('n_perizinan'))
                ->get_by_id($trtanggal_survey_id);

            if (count($data_rekomendasi) > 0) {
                $this->load->model('unitkerja/trunitkerja');
                $unit_kerja = new trunitkerja();
                $unit_kerja->get_by_id($data_rekomendasi->trunitkerja_id);

                $data['id'] = $tim_teknis_id;
                $data['rekomendasi'] = $data_rekomendasi->rekomendasi;
                $data['ket_rekomendasi'] = $data_rekomendasi->ket_rekomendasi;
                $data['instansi_teknis'] = $unit_kerja->n_unitkerja;

                $data['jenis_kegiatan'] = $data_rekomendasi->jenis_kegiatan;
                $data['nama_tim'] = $data_rekomendasi->nama_tim;
                $data['nip'] = $data_rekomendasi->nip;
                $data['nama_atasan_tim'] = $data_rekomendasi->nama_atasan_tim;
                $data['nip_atasan_tim'] = $data_rekomendasi->nip_atasan_tim;
                $data['jabatan_atasan_tim'] = $data_rekomendasi->jabatan_atasan_tim;
                $data['trtanggal_survey'] = $data_tanggal_survey;
                $this->load->vars($data);
                $this->session_info['page_name'] = "Isi Rekomendasi";
                $this->template->build('edit', $this->session_info);
            } else {
                $this->redirect('rekomendasi');
            }
        } else {
            $this->redirect('rekomendasi');
        }
    }

    public function save()
    {
        $id = $this->input->post('id');
        $rekomendasi = $this->input->post('rekomendasi');
        $ket_rekomendasi = $this->input->post('ket_rekomendasi');
        $jenis_kegiatan = $this->input->post('jenis_kegiatan');
        $nama_tim = $this->input->post('nama_tim');
        $nip = $this->input->post('nip');
        $nama_atasan_tim = $this->input->post('nama_atasan_tim');
        $nip_atasan_tim = $this->input->post('nip_atasan_tim');
        $jabatan_atasan_tim = $this->input->post('jabatan_atasan_tim');

        $tim_teknis = $this->tim_teknis->get_by_id($id);

        if ($tim_teknis->id) {
            $tim_teknis->rekomendasi = $rekomendasi;
            $tim_teknis->ket_rekomendasi = $ket_rekomendasi;
            $tim_teknis->jenis_kegiatan = $jenis_kegiatan;

            //Disable berdasarkan request dari Kominfo
            /*$tim_teknis->nama_tim = $nama_tim;
            $tim_teknis->nip = $nip;
            $tim_teknis->nama_atasan_tim = $nama_atasan_tim;
            $tim_teknis->nip_atasan_tim = $nip_atasan_tim;
            $tim_teknis->jabatan_atasan_tim = $jabatan_atasan_tim;*/

            if (!$tim_teknis->save()) {
                echo '<p>' . $this->tim_teknis->error->string . '</p>';
            } else {
                ## Cek Penentuaan masuk izin ditolak atau tidak ##
                $trtanggal_survey_id = $tim_teknis->trtanggal_survey_id;

                //Insert ke tracking status
                $this->load->model('survey/trtanggal_survey');
                $trtanggal_survey = new trtanggal_survey();
                $trtanggal_survey->get_by_id($trtanggal_survey_id);
                $id_permohonan = $trtanggal_survey->tmpermohonan->id;

//                if($this->check_rekomendasi_all($trtanggal_survey_id)){ //Jika semua merekomendasikan
                if(!$this->__is_rejected_permohonan($id_permohonan)){//jika belum masuk izin ditolak
                    if ($this->is_not_recommended($trtanggal_survey_id)) { //Jika ada yang tidak merekomendasikan
                        $this->load->model('permohonan/trlangkah_perizinan');
                        $status_skr = $this->_status_rekomendasi;// Rekomendasi [[Lihat tabel trstspermohonan]]
                        $this->__rejectPermohonan($id_permohonan, $status_skr);

                    } elseif ($this->check_rekomendasi_all($trtanggal_survey_id)) {//Jika semua sudah merekomendasikan
                        $kelompok_izin = $trtanggal_survey->tmpermohonan->trperizinan->trkelompok_perizinan->get();
                        $status_izin = $trtanggal_survey->tmpermohonan->trstspermohonan->get();
                        $status_skr = $this->_status_rekomendasi;// Rekomendasi [[Lihat tabel trstspermohonan]]

                        $this->load->model('permohonan/trlangkah_perizinan');
                        $langkah_perizinan = new trlangkah_perizinan();
                        if ($status_izin->id == $status_skr) {
                            $status_baru = $langkah_perizinan->nextStep($kelompok_izin->id, $status_izin->id);
                            $this->__input_tracking_progress($id_permohonan, $status_skr, $status_baru);
                        }
                    }
                }
                #############################################

                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                //$p = $this->db->query("call log ('Rekomendasi','Isi Rekomendasi :" . $id_permohonan . "','" . $tgl . "','" . $u_ser . "')");
				$this->rat->log('Save',6,$this->session->userdata('id_auth'),$id_permohonan);
                redirect('rekomendasi');
            }
        }
    }

    /**
     * Fungsi untuk mengecek apakah sudah semua memberi rekomendasi ya.
     * Jika sudah semua, Insert tracking status ke Penetapan Izin
     */
    private function check_rekomendasi_all($trtanggal_survey_id)
    {
        $all_recommend = true;
        $recommended_code = array('direkomendasikan');//Tidak usah sama casenya karena nanti dilower case
        $tim_teknis = $this->tim_teknis->where('trtanggal_survey_id', $trtanggal_survey_id);
        foreach ($tim_teknis->get() as $tim) {
            if (!in_array(strtolower($tim->rekomendasi), $recommended_code)) {
                $all_recommend = false;
                break;
            }
        }
        return $all_recommend;
    }

    /**
     * Fungsi untuk mengecek apakah sudah semua memberi rekomendasi ya.
     * Jika sudah semua, Insert tracking status ke Penetapan Izin
     */
    private function is_not_recommended($trtanggal_survey_id)
    {
        $not_recommended = false;
        $not_recommended_code = array('tidak direkomendasikan');//Tidak usah sama casenya karena nanti dilower case
        $tim_teknis = $this->tim_teknis->where('trtanggal_survey_id', $trtanggal_survey_id);
        foreach ($tim_teknis->get() as $tim) {
            if (in_array(strtolower($tim->rekomendasi), $not_recommended_code)) {
                $not_recommended = true;
                break;
            }
        }
        return $not_recommended;
    }
} 
