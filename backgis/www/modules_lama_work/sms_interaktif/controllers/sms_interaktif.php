<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Modul untuk menerima SMS Interaktif yang diforward oleh SMS Gateway
 * @author  Indra Halim
 * @since   1.0
 *
 */
class Sms_interaktif extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->load->model('sms_masuk');
        $this->sms_masuk = new sms_masuk();

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

    public function index(){
        $data['list'] = $this->sms_masuk->order_by('tgl_masuk','desc')->order_by('nama','desc')->get();
        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#sms_interaktif').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "SMS Interaktif";
        $this->template->build('index', $this->session_info);
    }

    public function view($id){
        $dataSms = $this->sms_masuk->get_by_id($id);
        if($dataSms->id){
            $data['data_sms'] = $dataSms;
            $this->load->vars($data);
            $this->session_info['page_name'] = "Detail SMS";
            $this->template->build('view', $this->session_info);
        }else{
            redirect('sms_interaktif/index');
        }
    }

    public function edit($id){
        $dataSms = $this->sms_masuk->get_by_id($id);
        if($dataSms->id){
            $data['data_sms'] = $dataSms;
            $js = "$(document).ready(function() {
                        $('#form').validate()
                   });";
            $this->template->set_metadata_javascript($js);
            $this->load->vars($data);
            $this->session_info['page_name'] = "Reply SMS";
            $this->template->build('edit', $this->session_info);
        }else{
            redirect('sms_interaktif/index');
        }
    }

    public function save(){
        $dataSms = $this->sms_masuk->get_by_id($this->input->post('id'));
        if($dataSms->id){
            $reply_sms = $this->input->post('reply_sms');
            $dataSms->reply_sms = $reply_sms;
            $sendSMS = $this->sms_masuk->send_sms($reply_sms, $dataSms->no_hp);
            if(!$sendSMS) {
                echo '<p>SMS tidak berhasil dikirim</p>';
            }else{
                $dataSms->replied = 1;
                $this->sms_masuk->save();
                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $g = $this->sql2($u_ser);
                $p = $this->db->query("call log ('Reply SMS Interaktif','Insert ".$dataSms->no_hp."','".$tgl."','".$u_ser."')");
                redirect('sms_interaktif');
            }
        }else{
            redirect('sms_interaktif');
        }
    }

    public function delete($id){
        $dataSms = $this->sms_masuk->get_by_id($id);
        if($dataSms->id){
            $dataSms->delete();
        }
        redirect('sms_interaktif');
    }

    public function sql2($u_ser)
    {
        $query = "select a.description
            from user_auth as a
            inner join user_user_auth as  x on a.id = x.user_auth_id
            inner join user as b on b.id = x.user_id
                where b.id = (select id from user where username='".$u_ser."')";
                $hasil = $this->db->query($query);
                return $hasil->row();
    }
}
?>