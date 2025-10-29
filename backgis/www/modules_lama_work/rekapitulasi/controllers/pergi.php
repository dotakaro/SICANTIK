<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of penjadwalan
 *
 * @author Eva
 */
class Pergi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->pergi = new tmpermohonan();
        $this->status = new trstspermohonan();
        $this->pergi = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->pergi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '6') {
                $enabled = TRUE;
                $this->pergi = new user_auth();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $pergi = new tmpermohonan();

        $pergi->get();
        $pergi->trperizinan->get();
        $pergi->trstspermohonan->get();
        $status = new trstspermohonan();

        $data['list_tahun'] = $this->pergi->group_by('d_tahun','ASC')->get();
       $data['list'] = $pergi->where_join_field('tmpermohonan_trperizinan','trperizinan_id')->get();
        //$data['list'] = $pergi->get();
        $data['jum1'] = $pergi->$status->where('trstspermohonan_id = 7')->count();
        $data['jum2'] = $pergi->$status->where('trstspermohonan_id = 8')->count();
        $data['jum3'] = $pergi->$status->where('trstspermohonan_id = 9')->count();



        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pergi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Pengambilan Izin Pergi/Ditolak";
        $this->template->build('pergi_list', $this->session_info);
    }


    public function filter() {

        $pergi = new tmpermohonan();
        $status = new trstspermohonan();
        $izin = new trperizinan();
        
        $pergi->where_join_field('tmpermohonan_trperizinan','trperizinan_id')->get();
        

        $data['list_tahun'] = $this->pergi->group_by('d_tahun','ASC')->get();
        $data['list'] = $pergi->where('d_tahun',$this->input->post('d_tahun'))->get();
        $data['jum1'] = $pergi->$status->where('trstspermohonan_id = 7')->count();
        $data['jum2'] = $pergi->$status->where('trstspermohonan_id = 8')->count();
        $data['jum3'] = $pergi->$status->where('trstspermohonan_id = 9')->count();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#pergi').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Rekapitulasi Pengambilan Izin Pergi/Ditolak";
        $this->template->build('pergi_list', $this->session_info);
    }

     public function view() {

        $perizinan = new trperizinan();
        $status = new trstspermohonan();
        $retribusi = new tmpermohonan();
        $data['range'] = '';

         $data['list'] = $retribusi->where_join_field('tmpermohonan_trperizinan','trperizinan_id')->get();
       // $data['list_tahun'] = $this->izin->group_by('d_tahun','ASC')->get();
       // $data['list'] = $this->perizinan->get();
        $data['jum1'] = $this->status->where('id',14)->get();
        $data['jum2'] = $this->status->where('id', 13)->count();
        $data['jum3'] = $this->status->where('id', 14)->count();


        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#izin').dataTable({
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
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Rekapitulasi Perizinan";
        $this->template->build('view_rekappergi', $this->session_info);
    }

    public function datalist() {

        $this->pergi->get();
        $this->pergi->set_json_content_type();
        echo $this->pergi->json_for_data_table();

    }
    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $user = new \user_mdl();
        $user->username = $this->input->post('user_name');
        $user->password = md5($this->input->post('password'));
        if(! $user->save()) {
            echo '<p>' . $u->error->string . '</p>';
        } else {
            $this->index();
        }
    }

    public function update() {

    }

    public function validate_input(){

    }

}
