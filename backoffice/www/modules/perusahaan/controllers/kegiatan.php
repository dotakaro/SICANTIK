<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Jenis Kegiatan class
 *
 * @author agusnur
 * Created : 08 Okt 2010
 *
 */

class Kegiatan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->kegiatan = new trkegiatan();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '3') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->kegiatan->order_by('id', 'ASC')->get();
        $this->load->vars($data);

        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                
                $(document).ready(function() {
                        oTable = $('#kegiatan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        
//        $this->session_info['page_name'] = "Data Jenis Kegiatan";
        $this->session_info['page_name'] = "Data Bidang Usaha";
        $this->template->build('kegiatan_list', $this->session_info);
    }

    public function create() {
        $data['nama']  = "";
        $data['keterangan']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
        $js_date = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
//        $this->session_info['page_name'] = "Tambah Kegiatan";
        $this->session_info['page_name'] = "Tambah Bidang Usaha";
        $this->template->build('kegiatan_edit', $this->session_info);
    }

    public function edit($id_edit = NULL) {
        $this->kegiatan->get_by_id($id_edit);
        $js_date = "
                $(document).ready(function() {
                    $(\"#tabs\").tabs();
                    $('#form').validate();
                } );
            ";
        $this->template->set_metadata_javascript($js_date);

        $data['nama'] = $this->kegiatan->n_kegiatan;
        $data['keterangan'] = $this->kegiatan->keterangan;
        $data['save_method'] = "update";
        $data['id'] = $this->kegiatan->id;

        $this->load->vars($data);
//        $this->session_info['page_name'] = "Edit Kegiatan";
        $this->session_info['page_name'] = "Edit Bidang Usaha";
        $this->template->build('kegiatan_edit', $this->session_info);
    }

    public function save() {
        $this->kegiatan->n_kegiatan = $this->input->post('nama');
        $this->kegiatan->keterangan = $this->input->post('keterangan');

        if(! $this->kegiatan->save()) {
            echo '<p>' . $this->kegiatan->error->string . '</p>';
        } else {
            $u_ser = $this->session->userdata('username');
            $tgl = date("Y-m-d H:i:s");
            $p = $this->db->query("call log ('Setting Umum','Insert kegiatan ".$this->input->post('nama')."','".$tgl."','".$u_ser."')");
            redirect('perusahaan/kegiatan');
        }

    }

    public function update() {
        $update = $this->kegiatan
                ->where('id', $this->input->post('id'))
                ->update(array('n_kegiatan' => $this->input->post('nama'),
                    'keterangan' => $this->input->post('keterangan')
                  ));
        if($update) {
            $u_ser = $this->session->userdata('username');
            $tgl = date("Y-m-d H:i:s");
            $p = $this->db->query("call log ('Setting Umum','Update kegiatan ".$this->input->post('nama')."','".$tgl."','".$u_ser."')");

            redirect('perusahaan/kegiatan');
        }
    }

    public function delete($id = NULL) {
        $this->kegiatan->where('id', $id)->get();
        $u_ser = $this->session->userdata('username');
        $tgl = date("Y-m-d H:i:s");
        $p = $this->db->query("call log ('Setting Umum','Delete kegiatan ".$this->kegiatan->n_kegiatan."','".$tgl."','".$u_ser."')");

        if($this->kegiatan->delete()) {
            redirect('perusahaan/kegiatan');
        }
    }

}

// This is the end of holiday class
