<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of pararel class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class Paralel extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->paralel = new trparalel();
        $this->perizinan = new trperizinan();
    }

    public function index() {
        $data['list'] = $this->paralel->get();
        $this->load->vars($data);
        $js =  "
                function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#paralel').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Perizinan Paralel";
        $this->template->build('list_paralel', $this->session_info);
    }

    public function add() {
        $js =  "
             $(document).ready(function() {
                    $('#form').validate();
                    $(\"#tabs\").tabs();
                } );
                $(document).ready(function() {
                    $('#listizin').multiselect().multiselectfilter({
                       show:'blind',
                       hide:'blind',
                       selectedText:'# of # selected'
                    });
                });";
        
        $this->template->set_metadata_javascript($js);
        $data['list'] = $this->perizinan->get();
      
        $data['izin_paralel'] = "";
        $data['save_method'] = "save";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit/Tambah Perizinan Paralel";
        $this->template->build('edit_paralel', $this->session_info);
    }

    public function save() {

        $this->paralel->n_paralel = $this->input->post('izin_paralel');
        $this->paralel->save();

        $listizin = $this->input->post('listizin');

        $this->paralel->where('n_paralel', $this->input->post('izin_paralel'))->get();
        $id_pararel = $this->paralel->id;

        foreach ($listizin as $list) {
            $this->paralel->where('n_paralel', $this->input->post('izin_paralel'))->get();
            $this->perizinan->where('id', $list)->get();
            $this->paralel->save($this->perizinan);
        }

        redirect('perizinan/paralel/');
    }

    public function delete($id_paralel = NULL) {
        $this->paralel->where('id', $id_paralel)->get();
        $this->paralel->delete();

        redirect('perizinan/paralel');
    }

    public function detail($id_paralel = NULL) {
        $this->paralel->where('id', $id_paralel)->get();
        $data['list'] = $this->paralel->trperizinan->get();
        $data['id_paralel'] = $id_paralel;
        $this->load->vars($data);
        $js =  "
                  function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#paralel').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Detail Perizinan Paralel";
        $this->template->build('list_paralel_detail', $this->session_info);
    }

    public function deletedetail($id_paralel = NULL, $id_izin = NULL) {
        $trparalel_trperizinan = new trparalel_trperizinan();
        $trparalel_trperizinan
            ->where('trparalel_id', $id_paralel)
            ->where('trperizinan_id', $id_izin)
            ->get();
        $trparalel_trperizinan->delete();
        
        redirect('perizinan/paralel/detail/' . $id_paralel);
    }

    public function adddetail($id_paralel = NULL) {
                $js =  "
                $(document).ready(function(){
                    $(\"#tabs\").tabs();
                });
                $(document).ready(function() {
                    $('#listizin').multiselect().multiselectfilter({
                       show:'blind',
                       hide:'blind',
                       selectedText:'# of # selected'
                    });
                });";

        $this->template->set_metadata_javascript($js);
        $data['list'] = $this->perizinan->get();
       
        $this->paralel->where('id', $id_paralel)->get();
        $data['list_izin_paralel'] = $this->paralel->where('id', $id_paralel)->get();
        $this->paralel->where('id', $id_paralel)->get();
        $data['izin_paralel'] = $this->paralel->n_paralel;
        $data['save_method'] = "update";
        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit/Tambah Perizinan Paralel";
        $this->template->build('edit_paralel', $this->session_info);
    }

    public function update() {
        $this->paralel->n_paralel = $this->input->post('izin_paralel');
        $this->paralel->save();

        $listizin = $this->input->post('listizin');

        $this->paralel->where('n_paralel', $this->input->post('izin_paralel'))->get();
        $id_pararel = $this->paralel->id;

        foreach ($listizin as $list) {
            $this->paralel->where('n_paralel', $this->input->post('izin_paralel'))->get();
            $this->perizinan->where('id', $list)->get();
            $this->paralel->save($this->perizinan);
        }

        redirect('perizinan/paralel/');
    }

}

// This is the end of pararel class
