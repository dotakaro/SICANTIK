<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Backup dan restore database
 *
 * @author Muhammad Rizky 
 * 
 */
class Log_backup extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->permohonan = new tmpermohonan();
        $this->perizinan = new trperizinan();
        $this->realisasi = new user_auth();

        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];
        $this->realisasi = NULL;

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '6') {
                $enabled = TRUE;
                $this->realisasi = new user_auth();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index()
    {
         $data['save_method'] = "save";
         $js = "
                $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                    $('a[rel*=detail]').facebox();
                } );
            ";
        $this->template->set_metadata_javascript($js);

        $this->load->vars($data);
        $this->session_info['page_name'] = "Restore Database";
        $this->template->build('edit_restore', $this->session_info);
    }

    public function save()
    {
//        $filename = 'uploads/database/'.$_FILES['file']['name'];
//        echo $filename;
//        if (file_exists($filename)) {
//            echo "The file $filename sudah ada";
//        } else {
//            echo "The file $filename tidak ada";
//        }
     
        $this->load->helper('file');
          if (!empty($_FILES['file']['name'])) {
            $config['upload_path'] = './uploads/database/';
            $config['allowed_types'] = 'zip';
            $config['max_size'] = '1000';
            $this->load->library('upload', $config);
            $this->upload->initialize($config);
            $field = 'file';
            if (!$this->upload->do_upload($field)) {
                $this->session->set_flashdata('pesan', $this->upload->display_errors());
                redirect('log/log_backup');
            } else {

                $filename = $_FILES['file']['name'];
                $this->restore($filename);
                delete_files('uploads/database/',$filename);
                $this->session->set_flashdata('pesan', 'Database Berhasil Disimpan!');
                redirect('log/log_backup');
           
            }
             
        }
   
    }


    public function backup() {

        $tanggal = date("Y-m-d");
        $nama = $_SESSION['my_db'].'.zip';
        $this->load->dbutil();
        $this->load->helper('download');
        $tabel = array( 'ignore'      => array('inbox','outbox','phones','sentitems'),   
                        'add_drop'    => TRUE,
                        'add_insert'  => TRUE
                        );
        $backup=& $this->dbutil->backup($tabel);
        force_download($nama,$backup);
        
    }
    
  
    public function restore($filename)
    {

        
//      $filename = $_FILES['file']['name'];
//        $query3 = "drop database something";
//        $sql3 = $this->db->query($query3);
//
//        $query1 = "create database something_2";
//        $sql1 = $this->db->query($query1);
//
//        $query2 = "use something_2";
//        $sql2 = $this->db->query($query2);
       
    $dosya ="uploads/database/".$filename;
    $veri = gzfile($dosya);
        foreach($veri as $i => $v)
        {
            if(substr($v, 0 ,1) == '#' || trim($v) == '') unset($veri[$i]);
        }
    $yeni = explode(";\n", implode("\n", $veri));

    foreach($yeni as $sql)
    {
    if(trim($sql) != '')
        {
        $s = $this->db->query(trim($sql));
        delete_files('uploads/database/',$filename);
         //echo "<script>window.location = '".base_url()."log/log_backup';</script>";
        }
               
    }
        
//        redirect('log/log_backup');
       

    }


}
