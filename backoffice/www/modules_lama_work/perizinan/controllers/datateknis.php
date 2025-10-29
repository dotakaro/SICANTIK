<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of datateknis class
 *
 * @author  Yana Supriatna
 * @ 9 Aug 2010
 *
 */

class Datateknis extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();

        $this->datateknis = new datateknis_mdl();
        $this->perijinan = new perijinan_mdl();
    }

    public function index() {
       
        $data['list'] = $this->datateknis->get();
        $data['list_ijin'] = $this->perijinan->order_by('id','ASC')->get();
        
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#datateknis').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Teknis";
        $this->template->build('list_datateknis', $this->session_info);
    }
    public function filterdata() {


        $this->datateknis->where('perijinan_id',$this->input->post('jenis_izin'));

        $data['list'] = $this->datateknis->get();
        $data['list_ijin'] = $this->perijinan->order_by('id','ASC')->get();

        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#datateknis').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Teknis ID Perijinan : ".$this->input->post('jenis_izin');
        $this->template->build('list_datateknis', $this->session_info);
    }

    public function create() {
        $data['n_property']  = "";
        $data['perijinan_id']  = "";
        $data['c_retribusi']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Data Teknis";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_jnsizin = NULL) {

        $this->datateknis->where('id', $id_jnsizin);
        $this->datateknis->get();

        $data['id'] = $this->datateknis->id;
        $data['n_property']     = $this->datateknis->n_property;
        $data['perijinan_id'] = $this->datateknis->perijinan_id;
        $data['c_retribusi']     = $this->datateknis->c_retribusi;
        $data['save_method'] = "update";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Data Teknis";
        $this->template->build('edit_datateknis', $this->session_info);

    }


    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->datateknis->get();
        $this->datateknis->set_json_content_type();
        echo $this->datateknis->json_for_data_table();

    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {

        $this->datateknis->id = $this->input->post('id');
        $this->datateknis->n_property = $this->input->post('n_property');
        $this->datateknis->perijinan_id = $this->input->post('perijinan_id');
        $this->datateknis->c_retribusi = $this->input->post('c_retribusi');
        if(! $this->datateknis->save()) {
            echo '<p>' . $this->datateknis->error->string . '</p>';
        } else {
            redirect('perijinan/datateknis');
        }

    }

    public function update() {
        $update = $this->datateknis
                ->where('id', $this->input->post('id'))
                ->update(array
                    (
                    'id' => $this->input->post('id'),
                    'n_property' => $this->input->post('n_property'),
                    'perijinan_id' => $this->input->post('perijinan_id'),
                    'c_retribusi' => $this->input->post('c_retribusi')
                    )
                        );
        if($update) {
            redirect('perijinan/datateknis');
        }
    }

    public function delete($id = NULL) {
        $this->datateknis->where('id', $id)->get();
        if($this->datateknis->delete()) {
            redirect('perijinan/datateknis');
        }
    }

    /*
     * Method for validating
     */

}

// This is the end of datateknis class