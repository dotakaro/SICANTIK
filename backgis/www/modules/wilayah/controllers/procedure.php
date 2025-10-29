<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Jenis Kegiatan class
 *
 * @author Muhammad Rizky 
 * Created : 08 Okt 2010
 *
 */

class Procedure extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->propinsi = new trpropinsi();

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
        $data['list'] = $this->propinsi->order_by('id', 'ASC')->get();
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
        
        $this->session_info['page_name'] = "Data Provinsi";
        $this->template->build('procedure_list', $this->session_info);
    }

    public function save()
    {
        $nama = $this->input->post('nama');
        $telp =  $this->input->post('telp');
        $alamat =  $this->input->post('alamat');
        $q = $this->db->query("call tambah('".$nama."','".$telp."','".$alamat."')");
        if ($q)
        {
            echo "berhasil";
        }
      

    }

    
}

// This is the end of holiday class
