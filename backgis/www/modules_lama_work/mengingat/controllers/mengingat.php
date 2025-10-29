<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of mengingat class
 *
 * @author  agusnur
 * Created : 19 Dec 2010
 *
 */

class Mengingat extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->trmengingat = new trmengingat();
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
                        oTable = $('#dasarhukum').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Mengingat SK";
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
        $this->session_info['page_name'] = "Setting Mengingat SK Untuk " . $this->trperizinan->n_perizinan;
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

        $data['list'] = $this->trmengingat->get();
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
        $this->session_info['page_name'] = "Tambah Mengingat SK";
        $this->template->build('edit', $this->session_info);
    }

    public function save() {
        $id_izin = $this->input->post('id_izin');
        $this->trperizinan->get_by_id($id_izin);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Insert mengingat SK ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");

        $this->trmengingat->deskripsi = $this->input->post('deskripsi');
        $this->trmengingat->type = $this->input->post('status');
        $perizinan = new trperizinan();
        $perizinan->get_by_id($id_izin);
        $this->trmengingat->save($perizinan);
        redirect('mengingat/detail'."/".$this->input->post('id_izin'));
    }

    public function delete($id_izin = NULL, $id_dasar_hukum = NULL) {
        $this->trperizinan->where('id', $id_izin)->get();
        $this->trmengingat->where('id', $id_dasar_hukum)->get();
        $this->trmengingat->delete($this->trperizinan);

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Delete mengingat SK ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");
        redirect('mengingat/detail'."/".$id_izin);
    }

    public function edit($id_izin = NULL, $id_dasar_hukum = NULL) {
        $data['id_izin'] = $id_izin;
        $data['id_dasar_hukum'] = $id_dasar_hukum;

        $this->trperizinan->where('id', $id_izin)->get();
        $this->trmengingat->where('id', $id_dasar_hukum);
        $this->trmengingat->get($this->trperizinan);

        $data['nama'] = $this->trmengingat->nama;
        $data['deskripsi'] = $this->trmengingat->deskripsi;
        $data['tgl_berlaku'] = $this->trmengingat->tgl_berlaku;
        $data['tgl_berakhir'] = $this->trmengingat->tgl_berakhir;
        $data['save_method'] = "update";
        $data['method'] = "editing";

        $rel = new trmengingat();
        $rel->get_by_id($id_dasar_hukum);

        $data['status_cont'] = $rel->type;

        $data['list'] = $this->trmengingat->get();
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
        $this->session_info['page_name'] = "Edit Mengingat SK";
        $this->template->build('edit', $this->session_info);
    }

    public function update() {
        $id_izin = $this->input->post('id_izin');
        $id_dasar_hukum = $this->input->post('id_dasar_hukum');

        $this->trperizinan->get_by_id($id_izin);
        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Surat Keputusan','Update mengingat SK ".$this->trperizinan->n_perizinan."','".$tgl."','".$u_ser."')");


        $this->trmengingat
                ->where('id', $id_dasar_hukum)
                ->update(array(
                    'deskripsi' => $this->input->post('deskripsi'),
                    'type' => $this->input->post('status')
                ));

        redirect('mengingat/detail'."/".$this->input->post('id_izin'));
    }
    
}