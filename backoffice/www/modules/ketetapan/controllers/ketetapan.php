<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of ketetapan class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Ketetapan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->ketetapan = new trketetapan();
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
        $data['list'] = $this->trperizinan->get();
        $this->load->vars($data);
        $js =  "
                $(document).ready(function() {
                        oTable = $('#ketetapan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Ketentuan Surat";
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
        $this->session_info['page_name'] = "Setting Ketentuan Surat Untuk " . $this->trperizinan->n_perizinan;
        $this->template->build('detail', $this->session_info);
    }

    public function add($id_izin = NULL) {
        $data['id_izin'] = $id_izin;
        $data['n_ketetapan'] = "";
        $data['save_method'] = "save";
        $data['method'] = "chaining";

        $data['list'] = $this->ketetapan->get();
        $data['list_izin'] = $this->trperizinan->where('id', $id_izin)->get();

        $js_date = "
            $(function() {
             $('#form').validate();
                $(\"#tabs\").tabs();
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Ketentuan Surat";
        $this->template->build('edit', $this->session_info);
    }

    public function save() {
        $id_izin = $this->input->post('id_izin');
        $this->ketetapan->n_ketetapan = $this->input->post('n_ketetapan');

        if($this->ketetapan->save()) {
            $this->trperizinan->get_by_id($id_izin);
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Surat Keputusan','Insert ketentuan surat ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");

            $this->trperizinan->where('id', $id_izin)->get();
            $this->ketetapan->where('n_ketetapan', $this->input->post('n_ketetapan'))->get();
            $this->trperizinan->save($this->ketetapan);

            redirect('ketetapan/detail'."/".$this->input->post('id_izin'));
        }
    }

    public function update() {
        $id_izin = $this->input->post('id_izin');
        $id_ketetapan = $this->input->post('id_ketetapan');

        $this->trperizinan->get_by_id($id_izin);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Update ketentuan surat ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");


        $this->ketetapan
                ->where('id', $id_ketetapan)
                ->update(array(
            'n_ketetapan' => $this->input->post('n_ketetapan')
        ));
        redirect('ketetapan/detail' . "/" . $id_izin);
    }

    public function savelist() {

        $id_izin = $this->input->post('id_izin');
        $dasarhukum_list = $this->input->post('dasarhukum');
        $dasarhukum_list_len = count($dasarhukum_list);

        for($i=0;$i<$dasarhukum_list_len;$i++) {
            $this->trperizinan->get_by_id($id_izin);
            $this->ketetapan->get_by_id($dasarhukum_list[$i]);
            $this->ketetapan->save($this->trperizinan);
        }

        redirect('ketetapan/detail'."/".$this->input->post('id_izin'));

    }

    public function delete($id_izin = NULL, $ketetapan = NULL) {
        $this->trperizinan->where('id', $id_izin)->get();
        $this->ketetapan->where('id', $ketetapan)->get();
        $this->ketetapan->delete($this->trperizinan);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Delete ketentuan surat ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");


        redirect('ketetapan/detail'."/".$id_izin);
    }

    public function edit($id_izin = NULL, $id_ketetapan = NULL) {
        $data['id_izin'] = $id_izin;
        $data['id_ketetapan'] = $id_ketetapan;
        $data['save_method'] = "update";
        $data['method'] = "editing";

        $this->ketetapan->where('id', $id_ketetapan)->get();
        $this->ketetapan->trperizinan->where('id', $id_izin)->get();
        $data['n_ketetapan'] = $this->ketetapan->n_ketetapan;

        $js_date = "
            $(function() {
                $(\"#tabs\").tabs();
                $('#form').validate();
            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Ketentuan Surat";
        $this->template->build('edit', $this->session_info);
        
    }   
}

// This is the end of ketetapan class
