<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of persyaratanizin class
 *
 * @author  Yana Supriatna
 * Created : 07 Aug 2010
 * Updated : 14 Aug 2010 (Agus N)
 *
 */

class Persyaratanizin extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->perizinan = new trperizinan();
        $this->persyaratanizin = new trsyarat_perizinan();

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

    public function index() {
        $data['list'] = $this->perizinan->order_by('id','ASC')->get();
        //$data['list'] = $this->persyaratanizin->get();
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#syaratizin').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Persyaratan Izin";
        $this->template->build('syarat_list', $this->session_info);
        /*echo "<pre>";
        print_r($data['list']);
        echo "</pre>";*/
    }

    public function detail($id = NULL) {
        $data['list'] = $this->perizinan->where('id', $id)->get();
        $data['id'] = $this->perizinan->id;
        $this->load->vars($data);
        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                
                $(document).ready(function() {
                        oTable = $('#syaratizin_detail').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Persyaratan Izin";
        $this->template->build('syarat_detail', $this->session_info);
    }

    public function create($id_izin = NULL) {
        $this->perizinan->get_by_id($id_izin);
        $data['syarat_list'] = $this->persyaratanizin->order_by('v_syarat ASC')->get();
        $data['perizinan_syarat'] = $this->perizinan->trsyarat_perizinan->get();
        
        $data['save_method'] = "save";
        $data['id'] = "";
        $data['perizinan_id'] = $id_izin;
        $data['v_syarat']  = "";
        $data['i_urut']  = "";
        $data['status']  = "ok";
        $data['si'] = "";
        $data['si2'] = "";
        $data['c_daftar_ulang'] = "";
        $data['c_baru'] = "ok";
        $data['c_perpanjangan'] = "ok";
        $data['c_ubah'] = "ok";

        $js =  "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $('#form').validate();
                    oTable = $('#syarat').dataTable({
                            \"bJQueryUI\": true,
                            \"sPaginationType\": \"full_numbers\"
                    });
                } );
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Persyaratan Izin";
        $this->template->build('syarat_edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_izin = NULL, $id_syarat = NULL) {

        $this->persyaratanizin->get_by_id($id_syarat);
        $this->persyaratanizin->trperizinan->include_join_fields()->get();

        $data['id'] = $this->persyaratanizin->id;
        $data['v_syarat']  = $this->persyaratanizin->v_syarat;
        $data['i_urut']  = $this->persyaratanizin->i_urut;
        $data['status']  = $this->persyaratanizin->status;

        /*
         * Baca beberapa status yang di-parse menjadi biner dan dikonvert jadi
         * desimal
         */
        $show_syarat = new trperizinan_syarat();
        $show_syarat
        ->where('trsyarat_perizinan_id', $id_syarat)
        ->where('trperizinan_id', $id_izin)->get();
        $var = $show_syarat->c_show_type;

        $rule = strval(decbin($var));
        if(strlen($rule) < 4) {
            $len = 4 - strlen($rule);
            $rule = str_repeat("0",$len) . $rule;
        }
        $arr_rule = str_split($rule);
        $data['si'] = $var;
        $data['si2'] = $rule;
        $data['c_daftar_ulang'] = $arr_rule[0];
        $data['c_baru'] = $arr_rule[1];
        $data['c_perpanjangan'] = $arr_rule[2];
        $data['c_ubah'] = $arr_rule[3];

        $data['perizinan_id']  = $id_izin;
        $data['save_method'] = "update";

        $js =  "
                $(document).ready(function() {
                 $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";

        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Persyaratan Izin";
        $this->template->build('syarat_edit', $this->session_info);

    }

    /*
     * Save and insert for manipulating data.
     */
    public function save() {        
        $data_id = new trsyarat_perizinan();
        
        $data_id->select_max('id')->get();
        $data_urut = $data_id->i_urut + 1;
        $this->persyaratanizin->v_syarat = $this->input->post('v_syarat');
        $this->persyaratanizin->status = $this->input->post('status');
        $this->persyaratanizin->i_urut = $data_urut;

        $perizinan = new trperizinan();
        $id_izin = $this->input->post('perizinan_id');
        $perizinan->get_by_id($id_izin);

        if(! $this->persyaratanizin->save($perizinan)) {
            echo '<p>' . $this->persyaratanizin->error->string . '</p>';
        } else {

         $tgl = date("Y-m-d H:i:s");
         $u_ser = $this->session->userdata('username');
         $p = $this->db->query("call log ('Setting Perizinan','Insert persyaratan izin','".$tgl."','".$u_ser."')");

            $rule = $this->input->post('c_daftar_ulang') .
                    $this->input->post('c_baru') .
                    $this->input->post('c_perpanjangan') .
                    $this->input->post('c_ubah');

            $c_show_type = bindec($rule);

            $perizinan = new trperizinan();
            $perizinan->get_by_id($id_izin);

            $syarat = new trsyarat_perizinan();
            $syarat->select_max('id')->get();
            $syarat->get_by_id($syarat->id);

            $syarat->set_join_field($perizinan, 'c_show_type', $c_show_type);
            redirect('perizinan/persyaratanizin/detail/'. $id_izin);
        }

    }
    
    
    private function updateperizinan($data, $id) 
    {
        $this->db->where('id', $id);
        $this->db->update('trsyarat_perizinan', $data);
    }
    
    
    public function save_list() {
        $id_izin = $this->input->post('perizinan_id');

        $syarat_list = $this->input->post('syarat');
        $status = $this->input->post('status');
        $c_baru = $this->input->post('c_baru');
        $c_perpanjangan = $this->input->post('c_tambah');
        $c_ubah = $this->input->post('c_ubah');
        $syarat_list_len = count($syarat_list);

        for($i=0;$i<$syarat_list_len;$i++) {
            
            
            
            $izin_syarat = new trperizinan_syarat();
            
            $dt_update=array(
                'status'=>$status[$i]
            );
            $this->updateperizinan($dt_update,$syarat_list[$i]);
            
            
            $izin_syarat->trperizinan_id = $id_izin;
            $izin_syarat->trsyarat_perizinan_id = $syarat_list[$i];
            if($c_baru[$i] === $syarat_list[$i]) $nilai_baru = 1; else $nilai_baru = 0;
            if($c_perpanjangan[$i] === $syarat_list[$i]) $nilai_perpanjangan = 1; else $nilai_perpanjangan = 0;
            if($c_ubah[$i] === $syarat_list[$i]) $nilai_ubah = 1; else $nilai_ubah = 0;
            $rule = $nilai_baru.
                    $nilai_baru.
                    $nilai_perpanjangan.
                    $nilai_ubah;

            $c_show_type = bindec($rule);
            $izin_syarat->c_show_type = $c_show_type;
            
            $izin_syarat->save();
        }

        redirect('perizinan/persyaratanizin/detail/'. $id_izin);
    }

    /*
     * Save and update for manipulating data.
     */
    public function update() {
        $id_izin = $this->input->post('perizinan_id');
        $update = $this->persyaratanizin
                ->where('id', $this->input->post('id'))
                ->update(array
                    (
                    'v_syarat' => $this->input->post('v_syarat'),
                    'status' => $this->input->post('status')
                    )
                        );
        if(! $update) {
            echo '<p>' . $this->persyaratanizin->error->string . '</p>';
        } else {

         $tgl = date("Y-m-d H:i:s");
         $u_ser = $this->session->userdata('username');
         $p = $this->db->query("call log ('Setting Perizinan','Update persyaratan izin','".$tgl."','".$u_ser."')");


            $rule = $this->input->post('c_daftar_ulang') .
                    $this->input->post('c_baru') .
                    $this->input->post('c_perpanjangan') .
                    $this->input->post('c_ubah');

            $c_show_type = bindec($rule);

            $perizinan = new trperizinan();
            $perizinan->get_by_id($id_izin);

            $syarat = new trsyarat_perizinan();
            $syarat->get_by_id($this->input->post('id'));

            $syarat->set_join_field($perizinan, 'c_show_type', $c_show_type);

            redirect('perizinan/persyaratanizin/detail/'. $id_izin);
        }
    }

    public function delete($id_izin = NULL, $id_syarat = NULL) {
        $izin_syarat = new trperizinan_syarat();
        $where_all = array('trperizinan_id' => $id_izin, 'trsyarat_perizinan_id' => $id_syarat);
        $izin_syarat->where($where_all)->get();
        $izin_syarat->delete();

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Perizinan','Delete persyaratan izin','".$tgl."','".$u_ser."')");

//        $syarat_izin = new trsyarat_perizinan();
//        $syarat_izin->where('id', $id_syarat)->get();
//        $syarat_izin->delete();

        redirect('perizinan/persyaratanizin/detail/'. $id_izin);

    }

//====//

    /*
     * Method for validating
     */

}

// This is the end of persyaratanizin class