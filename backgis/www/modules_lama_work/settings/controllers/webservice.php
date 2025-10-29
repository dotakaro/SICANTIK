<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of webservice class
 *
 * @author  AgusN
 * @since   1.0
 *
 */
class Webservice extends WRC_AdminCont {

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
        $this->settings->where('name', 'app_web_service')->get();
        $service = $this->settings->value;
        $data['status'] = $this->settings->status;
        $data['service'] = $service;

        //penduduk
         $this->settings->where('name', 'web_service_penduduk')->get();
        $service2 = $this->settings->value;
        $data['status2'] = $this->settings->status;
        $data['service2'] = $service2;
        
        $data['save_method'] = "update";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Web Service";
        $this->template->build('web_service_edit', $this->session_info);
    }

    public function update() {
        $service = $this->input->post('service');
        $status = $this->input->post('online');

        $service2 = $this->input->post('penduduk');
        $status2 = $this->input->post('online2');

        $u_ser = $this->session->userdata('username');
        $tgl = date("Y-m-d H:i:s");
        $p = $this->db->query("call log ('Setting Umum','Setting Webservice','".$tgl."','".$u_ser."')");


//echo $service."-".$status;
        $update = $this->settings
                ->where('name', 'app_web_service')
                ->update(array('value' => $service,
                    'status' => $status
                ));

         $update2 = $this->settings
                ->where('name', 'web_service_penduduk')
                ->update(array('value' => $service2,
                    'status' => $status2
                ));
        
        redirect('settings/webservice');
    }

}
