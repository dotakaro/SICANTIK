<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of alurizin class
 *
 * @author  Yana Supriatna
 * Created : 7 Aug 2010
 *
 */

class alurizin extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->alurizin = new tralur_perizinan();
    }

    public function index() {
        $perizinan = new trperizinan();
        
        $data['list_izin'] = $perizinan->get();
        $data['list'] = $this->alurizin->get();
        $this->load->vars($data);

        $js =  "
                $(document).ready(function() {
                        oTable = $('#alurizin').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });
                } );
                ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data Alur Izin";
        $this->template->build('listalur', $this->session_info);
    }

    public function create() {
        $perizinan = new trperizinan();
        $peran = new user_auth();

        $data['list_izin'] = $perizinan->order_by('n_perizinan','ASC')->get()->all;
        $data['list_peran'] = $peran->order_by('description','ASC')->get()->all;

        $data['perizinan_id']  = "";
        $data['peran_id']  = "";
        $data['v_jam']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Alur Izin";
        $this->template->build('editalur', $this->session_info);
    }

    /*
     * edit is a method to show page for updating data
     */
    public function edit($id_syarat = NULL) {
        $perizinan = new trperizinan();
        $peran = new user_auth();

        $data['list_izin'] = $perizinan->order_by('n_perizinan','ASC')->get()->all;
        $data['list_peran'] = $peran->order_by('description','ASC')->get()->all;


        $this->alurizin->where('id', $id_syarat);
        $this->alurizin->get();

        $data['id'] = $this->alurizin->id;
        $data['perizinan_id']  = $this->alurizin->perizinan_id;
        $data['peran_id']  = $this->alurizin->peran_id;
        $data['v_jam']  = $this->alurizin->v_jam;
        $data['save_method'] = "update";

        $this->load->vars($data);
        $this->session_info['page_name'] = "Edit Alur Izin";
        $this->template->build('editalur', $this->session_info);

    }


    /**
     * Not yet in use, because of some limit of dataTables
     */
    public function datalist() {

        $this->alurizin->get();
        $this->alurizin->set_json_content_type();
        echo $this->alurizin->json_for_data_table();

    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {

//        echo"Jenis Izin : ". $this->input->post('opsi_izin') ."<br>";
//        echo"Peran : ". $this->input->post('opsi_peran')."<br>";
        
        $this->alurizin->perizinan_id = $this->input->post('opsi_izin');
        $this->alurizin->v_syarat = $this->input->post('opsi_peran');
        $this->alurizin->i_urut = $this->input->post('v_jam');

        $perizinan = new trperizinan();
        $perizinan->where('id', $this->input->post('opsi_izin'))->get();
        $peran = new user_auth();
        $peran->where('id', $this->input->post('opsi_peran'))->get();


        if ($this->alurizin->save(array($peran))) {
            redirect('perizinan/alurizin');
        } else {
            echo '<p>' . $this->alurizin->error->string . '</p>';
        }

    }

    public function update() {
        $update = $this->alurizin
                ->where('id', $this->input->post('id'))
                ->update(array
                    (
                    'id' => $this->input->post('id'),
                    'perizinan_id' => $this->input->post('perizinan_id'),
                    'peran_id' => $this->input->post('peran_id'),
                    'v_jam' => $this->input->post('v_jam')
                    )
                        );
        if($update) {
            redirect('perizinan/alurizin');
        }
    }

    public function delete($id = NULL) {
       $hapus = $this->alurizin->where('id',$id)->get();
        if($hapus) {
            $hapus = $this->alurizin->delete();
            if ($hapus) {
            redirect('perizinan/alurizin'); }
        }
    }
    /*
     * Method for validating
     */

}

// This is the end of alurizin class