<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Muhammad Rizky
 * 
 */
class Log extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->realisasi = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->realisasi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '6') {
                $enabled = TRUE;
                $this->realisasi = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
//        $data['list_tahun'] = $this->permohonan->group_by('d_tahun','ASC')->get();
        
      $tgla = $this->input->post('tgla')==null?$this->lib_date->set_date(date('Y-m-d'), -2):$this->input->post('tgla');
      $tglb = $this->input->post('tglb')==null?$this->lib_date->set_date(date('Y-m-d'), 0):$this->input->post('tglb');
      $data['tgla'] = $tgla;
      $data['tglb'] = $tglb;
      $data['user'] = $this->sql($tgla,$tglb);

         $js = "
            $(document).ready(function() {
                oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                    \"sPaginationType\": \"full_numbers\"
                        });
            });

            $(document).ready(function() {
                $('.monbulan').datepicker({
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

        $this->load->vars($data);
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Log Activity";
        $this->template->build('log_list', $this->session_info);
    }

    public function view() {
        
       $tgla = $this->input->post('tgla');
       $tglb = $this->input->post('tglb');
     
       $data['user'] = $this->sql($tgla,$tglb);
       $data['tgla'] = $tgla;
       $data['tglb'] = $tglb;
       
       $this->load->vars($data);
        $js = "
            $(document).ready(function() {
                oTable = $('#monitoring').dataTable({
                                \"bJQueryUI\": true,
                                    \"sPaginationType\": \"full_numbers\"
                        });
            });

            $(document).ready(function() {
                $('.monbulan').datepicker({
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

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Log Activity";
        $this->template->build('log_list', $this->session_info);

        }
        
        public function hapus($id)
        {
            $log = new tmlogactivity();
            $log->get_by_id($id);
            $log->delete();
            redirect ('log');
        }

        public function sql($a,$b)
        {
            $query = "select * from tmlogactivity where
            SUBSTRING(action_date,1,10) >= '".$a."'
            and SUBSTRING(action_date,1,10) <= '".$b."'";
            
            $hasil = $this->db->query($query);
            return $hasil->result();
        }


   


}
