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

class Hitung_retribusi extends WRC_AdminCont {

    private $_status_retribusi = 18;//Perhitungan Retribusi

    public function __construct() {
        parent::__construct();
        $this->load->model('retribusi');
        $this->retribusi = new retribusi();
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

    public function index() {
//        $this->_check_auth();

        $is_administrator = $this->__is_administrator();
        if(!$is_administrator){
            $current_username = $this->session->userdata('username');
            $this->load->model('pengguna/user');
            $this->user = new user();
            $current_user = $this->user->where('username', $current_username)->get();
            $current_unitkerja = $current_user->tmpegawai->trunitkerja->get();
        }

        $tgla = $this->input->post('tgla');
        $tglb = $this->input->post('tglb');
        $now = $this->lib_date->get_date_now();
        $tgl_before = $this->lib_date->set_date($now, -2);
        $tgl_now = $this->lib_date->set_date($now, 0);

        if($tgla && $tglb){
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }else{
            $tgla = $tgl_before;
            $tglb = $tgl_now;
            $data['tgla'] = $tgla;
            $data['tglb'] = $tglb;
        }
        $status_hitung_retribusi = $this->_status_retribusi;//Lihat di tabel trstspermohonan
        $this->load->model('pelayanan/tmpermohonan');
        $permohonan = new tmpermohonan();
//        $permohonan->where_in_related('trperizinan/trkelompok_perizinan', 'id', $this->__get_izin_dengan_tarif()); //ambil kelompok izin bertarif
        $permohonan->where('d_terima_berkas >=',$tgla);
        $permohonan->where('d_terima_berkas <=',$tglb);
        $permohonan->where_related('trperizinan/user','id',$this->session->userdata('id_auth'));
        $permohonan->where_in('trunitkerja_id',$this->__get_current_unitakses());
        $permohonan->where_related('tmtrackingperizinan/trstspermohonan','id',$status_hitung_retribusi);
//        if(!$is_administrator){
            //Jika user ini berada di unit kerja yang berhak menghitung retribusi untuk suatu izin
//            $permohonan->where_related('trperizinan/setting_formula_retribusi/setting_formula_detail','trunitkerja_id',$current_unitkerja->id);
//        }//TODO Aktifkan kembali filtering untuk unit yang dapat melakukan perhitungan retribusi

        //$permohonan->where_related('tmbap','bap_id','IS NOT NULL'); //cari yang sudah ada bap nya

        $data['list'] = $permohonan->order_by('d_terima_berkas','DESC')->get();
        $this->load->vars($data);
        $js =  "
                $(document).ready(function() {
                        oTable = $('#hitung_retribusi').dataTable({
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
        $this->session_info['page_name'] = "Perhitungan Retribusi";
        $this->template->build('index', $this->session_info);
    }

    public function hitung($permohonan_id = null){
        if($permohonan_id !=''){
//            $this->_check_auth();

            //Ambil data permohonan
            $this->load->model('pelayanan/tmpermohonan');
            $permohonan = new tmpermohonan();
            $data_permohonan = $permohonan
                ->include_related('tmpemohon',array('n_pemohon'))
                ->include_related('trperizinan',array('n_perizinan'))
                ->get_by_id($permohonan_id);

            if(count($data_permohonan) > 0){
                $nilai_retribusi = 0;
                $retribusi_id = null;
                $trperizinan_id = $data_permohonan->trperizinan->id;

                //coba cari apakah sudah ada perhitungan retribusi untuk permohonan ini
                $existing_retribusi = $this->retribusi->where('tmpermohonan_id', $permohonan_id)->get();
                if(count($existing_retribusi) > 0){
                    //Jika ada, load datanya
                    $nilai_retribusi = $existing_retribusi->nilai_retribusi;
                    $retribusi_id = $existing_retribusi->id;
                }

                //Ambil formula
                $this->load->model('setting_formula/setting_formula_retribusi');
                $this->setting_formula_retribusi = new setting_formula_retribusi();
                $formula_retribusi = $this->setting_formula_retribusi->get_formula_javascript($trperizinan_id);

                //Ambil data Setting Tarif untuk Jenis Izin yang diajukan
                $this->load->model('setting_tarif/setting_tarif_item');
                $setting_tarif_item = new setting_tarif_item();
                $list_item_tarif = $setting_tarif_item->where('trperizinan_id', $trperizinan_id)->where('deleted',0)->get();

                $data['data_permohonan'] = $data_permohonan;
                $data['list_item_tarif'] = $list_item_tarif;
                $data['nilai_retribusi'] = $nilai_retribusi;
                $data['retribusi_id'] = $retribusi_id;
                $data['permohonan_id'] = $permohonan_id;
                $data['formula_retribusi'] = $formula_retribusi;
                $this->load->vars($data);
                $this->session_info['page_name'] = "Hitung Retribusi";
                $this->template->build('hitung', $this->session_info);
            }else{
                $this->redirect('hitung_retribusi');
            }
        }else{
            $this->redirect('hitung_retribusi');
        }
    }

    public function view($permohonan_id = null){
        if($permohonan_id !=''){
//            $this->_check_auth();

            //Ambil data permohonan
            $this->load->model('pelayanan/tmpermohonan');
            $permohonan = new tmpermohonan();
            $data_permohonan = $permohonan->get_by_id($permohonan_id);

            if(count($data_permohonan) > 0){
                $nilai_retribusi = 0;
                $retribusi_id = null;
                $trperizinan_id = $data_permohonan->trperizinan->id;

                //coba cari apakah sudah ada perhitungan retribusi untuk permohonan ini
                $existing_retribusi = $this->retribusi->where('tmpermohonan_id', $permohonan_id)->get();
                if(count($existing_retribusi) > 0){
                    //Jika ada, load datanya
                    //$nilai_retribusi = $existing_retribusi->nilai_retribusi;
                    //$retribusi_id = $existing_retribusi->id;

                    //Ambil data Setting Tarif untuk Jenis Izin yang diajukan
                    //$this->load->model('setting_tarif/setting_tarif_item');
                    //$setting_tarif_item = new setting_tarif_item();
                    //$list_item_tarif = $setting_tarif_item->where('trperizinan_id', $trperizinan_id)->get();

                    //$data['list_item_tarif'] = $list_item_tarif;
                    //$data['nilai_retribusi'] = $nilai_retribusi;
                    $data['retribusi'] = $existing_retribusi;
                    $this->load->vars($data);
                    $this->session_info['page_name'] = "Lihat Hasil Perhitungan Retribusi";
                    $this->template->build('view', $this->session_info);
                }
            }else{
                $this->redirect('hitung_retribusi');
            }
        }else{
            $this->redirect('hitung_retribusi');
        }
    }

    public function save(){
        $id = $this->input->post('id');
        $nilai_retribusi = $this->input->post('nilai_retribusi');
        $permohonan_id = $this->input->post('tmpermohonan_id');
        $jenis_perhitungan = $this->input->post('jenis_perhitungan');

        $this->load->model('pelayanan/tmpermohonan');
        $permohonan = new tmpermohonan();
        $permohonan->get_by_id($permohonan_id);
        $trperizinan_id = $permohonan->trperizinan->id;

        $this->retribusi->id = $id;
        $this->retribusi->nilai_retribusi = $nilai_retribusi;
        $this->retribusi->tmpermohonan_id = $permohonan_id;

        if(! $this->retribusi->save()) {
            echo '<p>' . $this->retribusi->error->string . '</p>';
        } else {
            //Simpan ke tabel tmbap
            $this->load->model('permohonan/tmbap');
            $all_bap = new tmbap();
            $all_bap->where_related('tmpermohonan','id',$permohonan_id)->select('id')->get();

            if(!$all_bap->id){//Jika belum ada BAP, asumsinya tidak pakai BAP, create 1 record untuk SKRD saja

                /*START Setting SKRD dari Report Component*/
                $this->load->model('report_component/Report_component_model');
                $this->report_component_model=new Report_component_model();
                $data_skrd = "SKRD";
                $no_skrd = null;
                $setting_component_skrd=$this->report_component_model->get_report_component($this->report_component_model->kode_skrd,$trperizinan_id, $permohonan_id);
                if(isset($setting_component_skrd['format_nomor']) &&
                    $setting_component_skrd['format_nomor']!=''){
                    $no_skrd = $setting_component_skrd['format_nomor'];
                }
                /*END Setting BAP dan SKRD dari Report Component*/
                $tim_teknis = $permohonan->trtanggal_survey->tim_teknis->get();
                foreach($tim_teknis as $tim){
                    $bap = new tmbap();
                    $bap->pendaftaran_id = $permohonan->pendaftaran_id;
                    $bap->tim_teknis_id = $tim->id;
                    $bap->nilai_retribusi = $nilai_retribusi;
                    $bap->nilai_bap_awal = $nilai_retribusi;
                    $update = $bap->save($permohonan);
                }
            }

            $all_bap->update_all('nilai_retribusi',$nilai_retribusi);
            $all_bap->update_all('nilai_bap_awal',$nilai_retribusi);

            if(isset($_POST['RetribusiDetail']) && !empty($_POST['RetribusiDetail'])){
                //Hapus jika sudah ada detail sebelumnya
                if($id !=''){
                    $existing_detail = new retribusi_detail();
                    $existing_detail->where('retribusi_id',$id)->get();
                    $existing_detail->delete_all();
                }

                foreach($_POST['RetribusiDetail'] as $retribusi_det){
                    $retribusi_detail = new retribusi_detail();
                    if(isset($retribusi_det['id']) && !empty($retribusi_det['id'])){
                        $retribusi_detail->id = $retribusi_det['id'];
                    }
                    $retribusi_detail->setting_tarif_item_id = $retribusi_det['setting_tarif_item_id'];
                    $retribusi_detail->jumlah = $retribusi_det['jumlah'];
                    $retribusi_detail->tarif_kategori = $retribusi_det['tarif_kategori'];
                    $retribusi_detail->subtotal = $retribusi_det['subtotal'];
                    $retribusi_detail->save($this->retribusi);
                }
            }elseif($jenis_perhitungan == 'manual'){
                if($id !=''){
                    $existing_detail = new retribusi_detail();
                    $existing_detail->where('retribusi_id',$id)->get();
                    $existing_detail->delete_all();
                }
            }

            /* Input Data Tracking Progress */
            $status_skr = $this->_status_retribusi;//Perhitungan Retribusi [Lihat tabel trstspermohonan]
            $status_izin = $permohonan->trstspermohonan->get();
            if($status_izin->id == $status_skr){
                $this->load->model('permohonan/trlangkah_perizinan');
                $langkah_perizinan = new trlangkah_perizinan();
                $next_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);
                $this->__input_tracking_progress($permohonan_id, $status_skr, $next_status);
            }

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Hitung Retribusi','Insert Hitung Retribusi permohonan:".$permohonan_id."','".$tgl."','".$u_ser."')");
            redirect('hitung_retribusi');
        }
    }
} 