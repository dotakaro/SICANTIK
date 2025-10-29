<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of dasarhukum class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Dasarhukum extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->trdasar_hukum = new trdasar_hukum();
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
                        oTable = $('#dasarhukum').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Dasar Hukum Surat";
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
                        oTable = $('#dasar_hukum').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Dasar Hukum Surat Untuk " . $this->trperizinan->n_perizinan;
        $this->template->build('detail', $this->session_info);
    }

    public function add($id_izin = NULL) {
        $data['id_izin'] = $id_izin;
        $data['nama'] = "";
        $data['deskripsi'] = "";
        $data['tgl_berlaku'] = "";
        $data['tgl_berakhir'] = "";
        $data['save_method'] = "save";
        $data['method'] = "chaining";

        $data['list'] = $this->trdasar_hukum->get();
        $data['list_izin'] = $this->trperizinan->where('id', $id_izin)->get();

        $data['status_cont'] = "";
        $js_date = "
            $(function() {
                $(\"#tabs\").tabs();
                $('#form').validate();
                $(\"#tgl_berakhir\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });
                
                $(\"#tgl_berlaku\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });

            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Dasar Hukum Surat";
        $this->template->build('edit', $this->session_info);
    }

    public function save() {
        $id_izin = $this->input->post('id_izin');
//        $this->trdasar_hukum->nama = $this->input->post('nama');
        $this->trdasar_hukum->deskripsi = $this->input->post('deskripsi');
//        $this->trdasar_hukum->tgl_berlaku = $this->input->post('tgl_berlaku');
//        $this->trdasar_hukum->tgl_berakhir = $this->input->post('tgl_berakhir');
        $this->trdasar_hukum->type = $this->input->post('status');
//        $perizinan = new trperizinan();
//        $perizinan->get_by_id($id_izin);
//        $this->trdasar_hukum->save($perizinan);

       
        $perizinan = new trperizinan();
        $perizinan->get_by_id($id_izin);
        $dasar = new trdasar_hukum();
        $dasar->type = $this->input->post('status');
        $dasar->deskripsi = $this->input->post('deskripsi');
        $dasar->save($perizinan);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Insert dasar hukum ".$perizinan->n_perizinan."','".$tgl."','".$u_ser."')");


        redirect('dasarhukum/detail'."/".$this->input->post('id_izin'));

//        if($this->trdasar_hukum->save()) {
//            $this->trperizinan->where('id', $id_izin)->get();
//            $this->trdasar_hukum->where('nama', $this->input->post('nama'))->get();
//            $this->trperizinan->save($this->trdasar_hukum);
//
//            $this->trdasar_hukum->where('nama', $this->input->post('nama'))->get();
//            $rel = new trdasar_hukum_trperizinan();
//            $rel->where(array(
//                'trdasar_hukum_id' => $this->trdasar_hukum->id,
//                'trperizinan_id' => $id_izin
//            ))->update(array(
//                'type' => $this->input->post('status')
//            ));
//
//            redirect('dasarhukum/detail'."/".$this->input->post('id_izin'));
//        }
    }

    public function savelist() {

        $id_izin = $this->input->post('id_izin');
        $dasarhukum_list = $this->input->post('dasarhukum');
        $dasarhukum_list_len = count($dasarhukum_list);

        for($i=0;$i<$dasarhukum_list_len;$i++) {
            $this->trperizinan->get_by_id($id_izin);
            $this->trdasar_hukum->get_by_id($dasarhukum_list[$i]);
            $this->trdasar_hukum->save($this->trperizinan);
        }

        redirect('dasarhukum/detail'."/".$this->input->post('id_izin'));

    }

    public function delete($id_izin = NULL, $id_dasar_hukum = NULL) {
        $this->trperizinan->where('id', $id_izin)->get();
        $this->trdasar_hukum->where('id', $id_dasar_hukum)->get();
        $this->trdasar_hukum->delete($this->trperizinan);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log  ('Setting Surat Keputusan','Delete dasar hukum ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");


        redirect('dasarhukum/detail'."/".$id_izin);
    }

    public function edit($id_izin = NULL, $id_dasar_hukum = NULL) {
        $data['id_izin'] = $id_izin;
        $data['id_dasar_hukum'] = $id_dasar_hukum;

        $this->trperizinan->where('id', $id_izin)->get();
        $this->trdasar_hukum->where('id', $id_dasar_hukum);
        $this->trdasar_hukum->get($this->trperizinan);

        $data['nama'] = $this->trdasar_hukum->nama;
        $data['deskripsi'] = $this->trdasar_hukum->deskripsi;
        $data['tgl_berlaku'] = $this->trdasar_hukum->tgl_berlaku;
        $data['tgl_berakhir'] = $this->trdasar_hukum->tgl_berakhir;
        $data['save_method'] = "update";
        $data['method'] = "editing";

        $rel = new trdasar_hukum();
        $rel->where(array(
            'id' => $id_dasar_hukum
        ))->get();

        $data['status_cont'] = $rel->type;

        $data['list'] = $this->trdasar_hukum->get();
        $data['list_izin'] = $this->trperizinan->where('id', $id_izin)->get();

        $js_date = "
            $(function() {
                $(\"#tabs\").tabs();
                $('#form').validate();
                $(\"#tgl_berakhir\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });

                $(\"#tgl_berlaku\").datepicker({
                    changeMonth: true,
                    changeYear: true,
                    dateFormat: 'yy-mm-dd',
                    closeText: 'X'
                });

            });
            ";
        $this->template->set_metadata_javascript($js_date);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Dasar Hukum Surat";
        $this->template->build('edit', $this->session_info);
    }

    public function update() {
        $id_izin = $this->input->post('id_izin');
        $id_dasar_hukum = $this->input->post('id_dasar_hukum');

        $perizinan = new trperizinan();
        $perizinan->where_related('trdasar_hukum','id',$id_dasar_hukum)->get();
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Update dasar hukum ".$perizinan->n_perizinan."','".$tgl."','".$u_ser."')");


        $this->trdasar_hukum
                ->where('id', $id_dasar_hukum)
                ->update(array(
                    'deskripsi' => $this->input->post('deskripsi'),
                    'type' => $this->input->post('status')
                ));

        $rel = new trdasar_hukum_trperizinan();
        $rel->where(array(
            'trdasar_hukum_id' => $id_dasar_hukum,
            'trperizinan_id' => $id_izin
        ))->update(array(
            'type' => $this->input->post('status')
        ));

        redirect('dasarhukum/detail'."/".$this->input->post('id_izin'));
    }
    
}

// This is the end of dasarhukum class
