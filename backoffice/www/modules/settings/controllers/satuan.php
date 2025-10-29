<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of satuan class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Satuan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->settings = new settings();

        /*$this->settings = NULL;

        $enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '3') {
                $enabled = TRUE;
                $this->settings = new settings();
            }
        }
		
        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {
        $this->settings->where('name','app_enum_satuan')->get();
        $satuan = $this->settings->value;

        if($satuan === NULL || $satuan === "") {
            $data['list'] = array();
        } else {
            $data['list'] = unserialize($satuan);
        }

        $this->load->vars($data);

        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                
                $(document).ready(function() {
                        oTable = $('#satuan').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);

        $this->session_info['page_name'] = "Setting Satuan";
        $this->template->build('satuan_list', $this->session_info);
    }

    public function create() {
        $data['satuan']  = "";
        $data['id'] = "";
        $data['save_method'] = "save";

        $js = "$(document).ready(function(){
                $('#form').validate();
                $(\"#tabs\").tabs();
        });";
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Satuan";
        $this->template->build('satuan_edit', $this->session_info);
    }

    public function save() {
        $this->settings->where('name','app_enum_satuan')->get();
        $satuan = $this->settings->value;

        if($satuan === NULL || $satuan === "") {
            $array = array();
        } else {
            $array = unserialize($satuan);
        }

        $array[] = $this->input->post('satuan');

        $u_ser = $this->session->userdata('username');
        $tgl = date("Y-m-d H:i:s");
        $p = $this->db->query("call log ('Setting Umum','Insert satuan ".$this->input->post('satuan')."','".$tgl."','".$u_ser."')");

        $update = $this->settings->
                where('name','app_enum_satuan')->
                update('value', serialize($array));

        redirect('settings/satuan');
    }

    public function delete($id) {
        $this->settings->where('name','app_enum_satuan')->get();
        $satuan = $this->settings->value;

        if($satuan === NULL || $satuan === "") {
            $array = array();
        } else {
            $array = unserialize($satuan);
        }

        unset($array[$id]);

        $u_ser = $this->session->userdata('username');
        $tgl = date("Y-m-d H:i:s");
        $p = $this->db->query("call log ('Setting Umum','Delete satuan','".$tgl."','".$u_ser."')");


        $update = $this->settings->
                where('name','app_enum_satuan')->
                update('value', serialize($array));

        redirect('settings/satuan');
    }

    public function edit($id) {
        $this->settings->where('name','app_enum_satuan')->get();
        $satuan = $this->settings->value;

        if($satuan === NULL || $satuan === "") {
            $array = array();
        } else {
            $array = unserialize($satuan);
        }

        $data['satuan']  = $array[$id];
        $data['id'] = $id;
        $data['save_method'] = "update";
        $js = "$(document).ready(function(){
                $('#form').validate();
                $(\"#tabs\").tabs();
        });";
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Satuan";
        $this->template->build('satuan_edit', $this->session_info);
    }

    public function update() {
        $this->settings->where('name','app_enum_satuan')->get();
        $satuan = $this->settings->value;

        if($satuan === NULL || $satuan === "") {
            $array = array();
        } else {
            $array = unserialize($satuan);
        }

        $array[$this->input->post('id')] = $this->input->post('satuan');

        $u_ser = $this->session->userdata('username');
        $tgl = date("Y-m-d H:i:s");
        $p = $this->db->query("call log ('Setting Umum','Update satuan ".$this->input->post('satuan')."','".$tgl."','".$u_ser."')");

        $update = $this->settings->
                where('name','app_enum_satuan')->
                update('value', serialize($array));

        redirect('settings/satuan');
    }

}

// This is the end of satuan class
