<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of header_instansi
 *
 * @author Yobi Bina Setiawan
 */
class Header_instansi extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->kabupaten = new trkabupaten();
        $this->tr_instansi = new Tr_instansi();
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

        $data['save_method'] = "update";
        $data['id'] = '';
        $data['nama_badan'] = $this->tr_instansi->get_by_id(9);
        $this->tr_instansi = new Tr_instansi();
        $data['kota'] = $this->tr_instansi->get_by_id(11);
        $this->tr_instansi = new Tr_instansi();
        $data['tlp'] = $this->tr_instansi->get_by_id(10);
        $this->tr_instansi = new Tr_instansi();
        $data['fax'] = $this->tr_instansi->get_by_id(13);
        $this->tr_instansi = new Tr_instansi();
        $data['alamat'] = $this->tr_instansi->get_by_id(12);
         $this->tr_instansi = new Tr_instansi();
        $data['kabupaten_i'] = $this->tr_instansi->get_by_id(4);
        $data['list_kabupaten'] = $this->kabupaten->order_by('n_kabupaten', 'ASC')->get();

        $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                    $('a[rel*=detail]').facebox();
                } );
            ";
        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Instansi";
        $this->template->build('edit', $this->session_info);
    }

    public function update() {
        $nama_badan = $this->input->post('nama_badan');
        $tlp = $this->input->post('tlp');
        $fax = $this->input->post('fax');
        $kota = $this->input->post('kabupaten_pemohon');
        $wilayah = $this->input->post('wilayah');
        $alamat = $this->input->post('alamat');
        $pecah = explode(".",$_FILES['logo']['name']);
        $new_name= "logo.".$pecah['1'];
      

        if (!empty($_FILES['logo']['name'])) {
            $config['upload_path'] = './uploads/logo/';
            $config['allowed_types'] = 'jpg|jpeg|png|gif';
            $config['max_height'] = '150';
            $config['max_width'] = '120';
            $config['max_size'] = '1000';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $field = 'logo';
            if (!$this->upload->do_upload($field)) {
                $this->session->set_flashdata('pesan', $this->upload->display_errors());
                redirect('header_instansi');
            } else {
                $dir = './uploads/logo/';
                $file = $_FILES['logo']['name'];
               $data=$this->upload->data();
                //rename($dir . $file, $dir .$new_name);
               $this->tr_instansi = new Tr_instansi;
               $data0 = array('value' => $data['file_name']);
               $this->tr_instansi->proses_update(14, $data0);

            }
        } /*else{
           $_FILES['logo']['name'] = "kominfo.png";
           $field = 'logo';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
           $this->upload->do_upload($field);
           $this->tr_instansi = new Tr_instansi;
           $data0 = array('value' => $_FILES['logo']['name']);
           $this->tr_instansi->proses_update(14, $data0);
        }*/
        

        $this->tr_instansi = new Tr_instansi;
        $data1 = array('value' => $nama_badan);
        $this->tr_instansi->proses_update(9, $data1);

        $this->tr_instansi = new Tr_instansi;
        $data2 = array('value' => $tlp);
        $this->tr_instansi->proses_update(10, $data2);

        $this->tr_instansi = new Tr_instansi();
        if (!empty($fax)) {
            $data3 = array('value' => $fax);
            $this->tr_instansi->proses_update(13, $data3);
            $this->tr_instansi = new Tr_instansi();
        }
        $data4 = array('value' => $kota);
        $this->tr_instansi->proses_update(4, $data4);

        $this->tr_instansi = new Tr_instansi();
        $data5 = array('value' => $alamat);
        $this->tr_instansi->proses_update(12, $data5);
        $this->tr_instansi = new Tr_instansi();
        //=======================

         $u_ser = $this->session->userdata('username');
         $tgl = date("Y-m-d H:i:s");
         $p = $this->db->query("call log ('Setting Umum','Setting instansi','".$tgl."','".$u_ser."')");


        $this->session->set_flashdata('pesan', '<font color="green" id="pesan">Data Berhasil Disimpan...Tekan Refresh (F5) untuk mendapatkan perubahan...</font>');
        redirect('header_instansi');
    }

    public function cek_logo() {
        $data['page_name'] = "Logo Instansi";        
        $data['logo']=$this->tr_instansi->get_by_id(14);
        $this->load->vars($data);
        $this->load->view('detail', $data);
    }

}

?>
