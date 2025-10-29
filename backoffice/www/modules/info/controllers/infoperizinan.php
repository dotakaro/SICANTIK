<?php

/**
 * Description of Informasi Perizinan
 *
 * @author agusnur
 * Created : 08 Okt 2010
 */
class InfoPerizinan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->perizinan = new trperizinan();
        $this->syarat_izin = new trsyarat_perizinan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '17') {
                $enabled = TRUE;
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->perizinan->order_by('id', 'ASC')->get();
        $this->load->vars($data);

        $js =  "$(document).ready(function() {
                        oTable = $('#perizinaninfo').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Informasi Perizinan";
        $this->template->build('infoperizinan_list', $this->session_info);
    }

    public function detail($no_izin = NULL) {
        $data_izin = $this->perizinan->get_by_id($no_izin);

        $data['data_izin'] = $data_izin;
        $data['list'] = $this->perizinan->where('id', $no_izin)->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#perizinandetail').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        
        $this->session_info['page_name'] = "Informasi Persyaratan Izin";
        $this->template->build('infoperizinan_detail', $this->session_info);
    }

}
