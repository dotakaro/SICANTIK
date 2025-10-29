<?php

/**
 * Description of Informasi Status Dokumen
 *
 * @author agusnur
 * Created : 29 Sep 2010
 */

class Informasi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
            $this->sk = new tmsk();

            /*$enabled = FALSE;
            $list_auths = $this->session_info['app_list_auth'];

            foreach ($list_auths as $list_auth) {
                if($list_auth->id_role === '4') {
                    $enabled = TRUE;
                }
            }
			
            if(!$enabled) {
                redirect('dashboard');
            }*/
        }

    public function index() {
        $perizinan = new trperizinan();
        $data['list'] = $perizinan->order_by('id', 'DESC')->get();
        $this->load->vars($data);

        $js =  "$(document).ready(function() {
                        oTable = $('#penyerahan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Informasi Status Dokumen";
        $this->template->build('informasi_list', $this->session_info);
    }
}

// This is the end of role class
