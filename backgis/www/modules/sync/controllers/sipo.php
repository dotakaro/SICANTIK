<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of monitoring class
 *
 * @author  Yogi Cahyana
 * @since   2011
 *
 */
class Lap_izin extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->pemohon = new tmpemohon();
        $this->permohonan = new tmpermohonan();
        $this->stspermohonan = new trstspermohonan();
        $this->perizinan = new trperizinan();
        $this->propinsi = new trpropinsi();
        $this->kabupaten = new trkabupaten();
        $this->kecamatan = new trkecamatan();
        $this->kelurahan = new trkelurahan();
        $this->monitoringkecamatan = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->monitoringkecamatan = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '10') {
                $enabled = TRUE;
                $this->monitoringkecamatan = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/

    }

    public function index() {
//        $data['listpemohon'] = $this->pemohon->limit(0)->get();
//        $data['list_ijin'] = $this->perizinan->order_by('id','ASC')->get();
//        $data = $this->_funcwilayah();

         $js =  "
                $(document).ready(function() {
                        oTable = $('#realisasi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );

             $(function() {
                $(\".tarif\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
            });

                function finishAjax(id, response){
                  $('#'+id).html(unescape(response));
                  $('#'+id).fadeIn();
                }
            ";
			$data['error']="";
        $this->template->set_metadata_javascript($js);
		$this->session_info['page_name'] = "Sinkronisasi SIPO";
        $this->template->build('sipo', $this->session_info);
    }
}
echo "JANGAN DIBUKA, LAGI DIKERJAKAN SAMA ORANG IT!!!!";