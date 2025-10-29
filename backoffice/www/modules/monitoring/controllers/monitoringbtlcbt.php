<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana
 * @since   1.0
 *
 */

class Monitoringbtlcbt extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->perijinan = new trperizinan();
        $this->monitoringbtlcbt = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringbtlcbt = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '2') {
                $enabled = TRUE;
                $this->monitoringbtlcbt = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['listpemohon'] = $this->pemohon->limit(0)->get();
        $data['listpermohonan'] = $this->permohonan->limit(0)->get();
        $data['list_ijin'] = $this->perijinan->order_by('id','ASC')->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Monitoring Batal dan Dicabut";
        $this->template->build('listbatalcabut', $this->session_info);
    }


     public function filterdata() {


        $this->permohonan->where('id',$this->input->post('listpemohon'));

        $data['listpemohon'] = $this->pemohon->get();
        $data['listpermohonan'] = $this->permohonan->get();
        $data['list_ijin'] = $this->perijinan->order_by('id','ASC')->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Monitoring Per Pemohon : ".$this->input->post('jenis_izin');
        $this->template->build('listbatalcabut', $this->session_info);
    }


}

// This is the end of monitoring class
