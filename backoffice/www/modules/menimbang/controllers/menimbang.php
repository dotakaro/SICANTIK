<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of trmenimbang class
 *
 * @author  agusnur
 * created : 19 Dec 2010
 *
 */

class Menimbang extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->trmenimbang = new trmenimbang();
        $this->trperizinan = new trperizinan();
        $this->load->helper('text');

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '2') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $data['list'] = $this->trperizinan->where('c_keputusan', 1)->get();
        $this->load->vars($data);
        $js =  "
                $(document).ready(function() {
                        oTable = $('#ketetapan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Menimbang SK";
        $this->template->build('list', $this->session_info);
    }

    public function detail($id_izin = NULL) {
        $data['list'] = $this->trperizinan->where('id', $id_izin)->get();
        $data['id'] = $this->trperizinan->id;
        $this->load->vars($data);
        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#ketetapan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Menimbang SK Untuk " . $this->trperizinan->n_perizinan;
        $this->template->build('detail', $this->session_info);
    }

    public function add($id_izin = NULL) {
        $data['id_izin'] = $id_izin;
        $data['deskripsi'] = "";
        $data['save_method'] = "save";
        $data['method'] = "chaining";

        $data['list'] = $this->trmenimbang->get();
        $data['list_izin'] = $this->trperizinan->where('id', $id_izin)->get();

        $js_date = "
            $(function() {
                $(\"#tabs\").tabs();
                $('#form').validate();
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Menimbang SK";
        $this->template->build('edit', $this->session_info);
    }

    public function save() {
        $id_izin = $this->input->post('id_izin');
        $this->trmenimbang->deskripsi = $this->input->post('deskripsi');

        if($this->trmenimbang->save()) {
            $this->trperizinan->where('id', $id_izin)->get();
            $this->trmenimbang->where('deskripsi', $this->input->post('deskripsi'))->get();
            $this->trperizinan->save($this->trmenimbang);

            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Surat Keputusan','Insert Menimbang SK ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");

            redirect('menimbang/detail'."/".$this->input->post('id_izin'));
        }
    }

    public function update() {
        $id_izin = $this->input->post('id_izin');
        $id_ketetapan = $this->input->post('id_ketetapan');
        $this->trperizinan->get_by_id($id_izin);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Update Menimbang SK ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");

        $this->trmenimbang
                ->where('id', $id_ketetapan)
                ->update(array(
            'deskripsi' => $this->input->post('deskripsi')
        ));
        redirect('menimbang/detail' . "/" . $id_izin);
    }

    public function savelist() {

        $id_izin = $this->input->post('id_izin');
        $dasarhukum_list = $this->input->post('dasarhukum');
        $dasarhukum_list_len = count($dasarhukum_list);

        for($i=0;$i<$dasarhukum_list_len;$i++) {
            $this->trperizinan->get_by_id($id_izin);
            $this->trmenimbang->get_by_id($dasarhukum_list[$i]);
            $this->trmenimbang->save($this->trperizinan);
        }

        redirect('menimbang/detail'."/".$this->input->post('id_izin'));

    }

    public function delete($id_izin = NULL, $ketetapan = NULL) {
        $this->trperizinan->where('id', $id_izin)->get();
        $this->trmenimbang->where('id', $ketetapan)->get();
        $this->trmenimbang->delete($this->trperizinan);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Delete Menimbang SK ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");

        redirect('menimbang/detail'."/".$id_izin);
    }

    public function edit($id_izin = NULL, $id_ketetapan = NULL) {
        $data['id_izin'] = $id_izin;
        $data['id_ketetapan'] = $id_ketetapan;
        $data['save_method'] = "update";
        $data['method'] = "editing";

        $this->trmenimbang->where('id', $id_ketetapan)->get();
        $this->trmenimbang->trperizinan->where('id', $id_izin)->get();
        $data['deskripsi'] = $this->trmenimbang->deskripsi;

        $js_date = "
            $(function() {
                $(\"#tabs\").tabs();
                 $('#form').validate();
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Menimbang SK";
        $this->template->build('edit', $this->session_info);
        
    }   
}

// This is the end of ketetapan class
