<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of webservice class
 *
 * @author  Muhammad Rizky
 * @since   1.0
 *
 */
class UnduhBerkas extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->settings = new settings();

        /*$this->settings = NULL;

        $enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if ($list_auth->id_role === '3') {
                $enabled = TRUE;
                $this->settings = new settings();
            }
        }
		
        if (!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $this->settings->where('name', 'unduh_berkas')->get();
        $alamat = $this->settings->value;
        $data['alamat'] = $alamat;

             
        $data['save_method'] = "update";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Unduh Berkas";
        $this->template->build('unduh_berkas_edit', $this->session_info);
    }

    public function update() {
        $service = $this->input->post('alamat');
        $cekString = strpos($service, $_SERVER['HTTP_HOST']);
        if ($cekString==true)
        {
            $this->session->set_flashdata('error', 'Penulisan alamat salah, Data tidak dapat disimpan');
            redirect('settings/unduhBerkas');
        }else
        {
            $u_ser = $this->session->userdata('username');
            $tgl = date("Y-m-d H:i:s");
            $p = $this->db->query("call log ('Setting Umum','Setting Unduh Berkas','".$tgl."','".$u_ser."')");
          
//echo $service."-".$status;
        $update = $this->settings
                ->where('name', 'unduh_berkas')
                ->update(array('value' => $service
                   
                ));
        $this->session->set_flashdata('sukses', 'Alamat berhasil disimpan');
        redirect('settings/unduhBerkas');
        } 
    }

}
