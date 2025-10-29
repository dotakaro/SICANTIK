<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of perizinan class
 * Class untuk Report Component
 * @author  Indra Halim
 * @since   1.0
 *
 */

class Notification_setting extends WRC_AdminCont {

    private $_statusNotifPetugas = array(19,6,18,5);//Daftar ID trstatuspermohonan yang ada notifikasi ke petugas

    public function __construct() {
        parent::__construct();

        $this->load->model('perizinan/trperizinan');
        $this->trperizinan=new trperizinan();
        $this->load->model('rekapitulasi/trstspermohonan');
        $this->trstspermohonan=new trstspermohonan();
        $this->load->model('setting_notifikasi');
        $this->setting_notifikasi = new setting_notifikasi();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function test_email(){
        $subject = 'Subject Email';
        $message = 'Contoh Kirim Email Lewat PHP';
        $to = 'indra.halimm@gmail.com';
        if($this->setting_notifikasi->send_email($message, $subject, $to)){
            echo '<p>Berhasil kirim email</p>';
        }else{
            echo '<p>Tidak berhasil kirim email</p>';
        }
        exit();
    }

    public function index(){
//        echo $this->setting_notifikasi->send_notification(6, 1341);//Testing Notifikasi Pembuatan BAP
//        echo $this->setting_notifikasi->send_notification(19, 746);//Testing Notifikasi Entry Data Tinjauan
//        echo $this->setting_notifikasi->send_notification(3, 749);//Testing Notifikasi Entry Data
//        echo $this->setting_notifikasi->send_notification(2, 1259);//[Server Live]Testing Notifikasi Pembuatan BAP
//        exit();
        $this->load->model('perizinan/trperizinan');
        $this->trperizinan = new trperizinan();

        //$data['list'] = $this->trperizinan->where_in_related_trkelompok_perizinan('id',$this->__get_izin_dengan_tarif())->get();
        $data['list'] = $this->trperizinan->get();
        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#notification_setting').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Notifikasi";
        $this->template->build('index', $this->session_info);
    }

    public function index_2() {

        if(!empty($_POST)){
            //Jika form sudah dipost, tentukan apakah action tersebut merupakan create atau open
            $clicked_button=$this->input->post('clicked_button');
            $setting_code=$this->input->post('setting_code');

            if($clicked_button=='btn_create'){
                redirect('notification_setting/add/'.$setting_code);
            }elseif($clicked_button=='btn_open'){
                $report_id=$this->input->post('report_id');
                redirect('notification_setting/edit/'.$report_id);
            }elseif($clicked_button=='btn_copy'){
                $report_id=$this->input->post('report_id');
                redirect('notification_setting/copy/'.$report_id);
            }else{
                redirect('notification_setting');
            }
        }
        $this->session_info['page_name'] = "Setting Notifikasi";
        $this->template->build('index', $this->session_info);
    }

    public function add($trperizinan_id){
        $perizinan = $this->trperizinan->get_by_id($trperizinan_id);
        if(!$perizinan->id){
            redirect('notification_setting');
        }
        $trkelompok_perizinan_id = $perizinan->trkelompok_perizinan->id;
        $data['perizinan'] = $perizinan;
        $data['list_tipe_notifikasi'] = array('-1'=>'pilih salah satu') + array('sms'=>'sms','email'=>'email');
        $data['list_status'] = $this->trstspermohonan->where_in_related('trlangkah_perizinan/trkelompok_perizinan','id', $trkelompok_perizinan_id)
                                                    ->or_where('id',9)//Izin Ditolak
                                                    ->order_by_related('trlangkah_perizinan','urut','ASC')->get();
        $data['status_notif_petugas'] = $this->_statusNotifPetugas;
        $this->load->vars($data);
        $js="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Add Setting Notifikasi";
        $this->template->build('add', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save_ajax() {
        $this->load->model('setting_notifikasi_detail');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('trperizinan_id', 'Nama Izin', 'required');
//        $this->form_validation->set_rules('tipe_notifikasi', 'Tipe Notifikasi', 'required');
        $response = array();
        $message = "";
        $success = false;
        $is_new = true;

        if ($this->form_validation->run()){
            $id = $this->input->post('id');
            $trperizinan_id = $this->input->post('trperizinan_id');
            if(isset($id) && $id!=''){
                $is_new = false;
                $this->setting_notifikasi->id = $id;
            }
//            $this->setting_notifikasi->tipe_notifikasi = $this->input->post('tipe_notifikasi');
            $this->setting_notifikasi->trperizinan_id = $trperizinan_id;

            if(! $this->setting_notifikasi->save()) {
                $message = $this->setting_notifikasi->error->string;
            } else {
                $id = $this->setting_notifikasi->id;
                if(isset($_POST['SettingNotifikasiDetail']) && !empty($_POST['SettingNotifikasiDetail'])){
                    //Hapus jika sudah ada detail sebelumnya
                    foreach($_POST['SettingNotifikasiDetail'] as $key=>$notifikasi_detail){
                        $setting_notifikasi_detail = new setting_notifikasi_detail();
                        if(isset($notifikasi_detail['id']) && !empty($notifikasi_detail['id'])){
                            $setting_notifikasi_detail->id = $notifikasi_detail['id'];
                        }
                        $setting_notifikasi_detail->trstspermohonan_id = $notifikasi_detail['trstspermohonan_id'];
                        $setting_notifikasi_detail->format_pesan = $notifikasi_detail['format_pesan'];
                        if(isset($notifikasi_detail['penerima_lain'])){
                            $setting_notifikasi_detail->penerima_lain = $notifikasi_detail['penerima_lain'];
                        }
                        $setting_notifikasi_detail->tujuan_notifikasi = $notifikasi_detail['tujuan_notifikasi'];
                        $setting_notifikasi_detail->tipe_notifikasi = $notifikasi_detail['tipe_notifikasi'];
                        $setting_notifikasi_detail->save($this->setting_notifikasi);
                    }

                }
                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $p = $this->db->query("call log ('Setting Notifikasi','Save Setting Notifikasi".$this->setting_notifikasi->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");
                $success = true;
            }
        }else{
            $message = 'Mohon isi form Setting Notifikasi';
        }
        $response['success']=$success;
        $response['message']=$message;
        $response['setting_notifikasi_id'] = $id;
        $response['is_new'] = $is_new;
        echo json_encode($response);exit();
    }

    public function edit($setting_notifikasi_id = null){
        $list_notifikasi_detail = array();
        $list_notifikasi_detail_petugas = array();
        $setting_notifikasi = $this->setting_notifikasi->get_by_id($setting_notifikasi_id);
        if(!$setting_notifikasi->id){
            redirect('notification_setting');
        }
        $perizinan = $this->trperizinan->get_by_id($setting_notifikasi->trperizinan_id);
        $trkelompok_perizinan_id = $perizinan->trkelompok_perizinan->id;

        ### BEGIN - Ambil Setting Notifikasi untuk ke Pemohon ###
        $setting_notifikasi_detail = $setting_notifikasi->setting_notifikasi_detail->where('tujuan_notifikasi',1)->get();
        if($setting_notifikasi_detail->id){
            foreach($setting_notifikasi_detail as $index=>$detail){
                $list_notifikasi_detail[$detail->trstspermohonan_id] = array(
                    'id'=>$detail->id,
                    'format_pesan'=>$detail->format_pesan,
                    'penerima_lain'=>$detail->penerima_lain,
                    'tipe_notifikasi'=>$detail->tipe_notifikasi
                );
            }
        }
        ### END - Ambil Setting Notifikasi untuk ke Pemohon ###

        ### BEGIN - Ambil Setting Notifikasi untuk ke Petugas ###
        $setting_notifikasi_detail_petugas = $setting_notifikasi->setting_notifikasi_detail->where('tujuan_notifikasi',2)->get();
        if($setting_notifikasi_detail_petugas->id){
            foreach($setting_notifikasi_detail_petugas as $index=>$detail){
                $list_notifikasi_detail_petugas[$detail->trstspermohonan_id] = array(
                    'id'=>$detail->id,
                    'format_pesan'=>$detail->format_pesan,
                    'penerima_lain'=>$detail->penerima_lain,
                    'tipe_notifikasi'=>$detail->tipe_notifikasi
                );
            }
        }
        ### END - Ambil Setting Notifikasi untuk ke Petugas ###

        $data['perizinan'] = $perizinan;
        $data['list_notifikasi_detail'] = $list_notifikasi_detail;
        $data['list_notifikasi_detail_petugas'] = $list_notifikasi_detail_petugas;
        $data['setting_notifikasi'] = $setting_notifikasi;
        $data['list_tipe_notifikasi'] = array('-1'=>'pilih salah satu') + array('sms'=>'sms','email'=>'email');
        $data['list_status'] = $this->trstspermohonan->where_in_related('trlangkah_perizinan/trkelompok_perizinan','id', $trkelompok_perizinan_id)
                                                    ->or_where('id',9)//Izin Ditolak
                                                    ->order_by_related('trlangkah_perizinan','urut','ASC')->get();
        $data['status_notif_petugas'] = $this->_statusNotifPetugas;
        $this->load->vars($data);
        $js="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Edit Setting Notifikasi";
        $this->template->build('edit', $this->session_info);
    }

    /*public function delete(){
        if(!empty($_POST['report_component_id'])){
            $report_component_id = $this->input->post('report_component_id');
            echo $this->Report_component_model->delete($report_component_id);
        }else{
            echo false;
        }
    }*/

    private function _remove_whitespace($string){
        return preg_replace("/\s+/", " ",$string );
    }

}
?>