<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of hitung retribusi class
 * Class untuk Hitung Retribusi
 * @author  Indra Halim
 * @since   1.0
 *
 */

class Kelompok_perizinan extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->load->model('pelayanan/trkelompok_perizinan');
        $this->load->model('rekapitulasi/trstspermohonan');
//        $this->load->model('trapi');
    }

    public function index() {
        $data['list'] = $this->trkelompok_perizinan->get();
        $this->load->vars($data);

        $js =  "function confirm_link(text){
                   if(confirm(text)){ return true;
                   }else{ return false; }
               }
               $(document).ready(function() {
                       oTable = $('#listKelompokIzin').dataTable({
                               \"bJQueryUI\": true,
                               \"sPaginationType\": \"full_numbers\"
                       });
               } );
               ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Kelompok Perizinan";
        $this->template->build('index', $this->session_info);
    }

    public function add(){
        $data['status_permohonan'] = $this->trstspermohonan->where('urutan_langkah IS NOT NULL')->order_by('urutan_langkah','ASC')->get();
        $data['save_method'] = 'save';
        $data['listMandatoryStatus'] = $this->trkelompok_perizinan->listMandatoryStatus;
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Add Kelompok Perizinan";
        $this->template->build('add', $this->session_info);
    }

    public function edit($kelompokPerizinanId){
        $getData = $this->trkelompok_perizinan->get_by_id($kelompokPerizinanId);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke index
            $this->session->set_flashdata('flash_message', array('message' => 'Maaf, Kelompok Izin yang anda akses tidak ada','class' => 'error'));
            redirect('kelompok_perizinan');
        }

        ## BEGIN - Ambil Data Status yang diset sebagai langkah perizinan untuk Kelompok Izin ini###
        $listStsPermohonanId = array();
        foreach($this->trkelompok_perizinan->trlangkah_perizinan as $indexLangkah=>$langkah){
            $listStsPermohonanId[] = $langkah->trstspermohonan_id;
        }
        $data['listStsPermohonanId'] = $listStsPermohonanId;
        ## END - Ambil Data Status yang diset sebagai langkah perizinan untuk Kelompok Izin ini###

        $data['status_permohonan'] = $this->trstspermohonan->where('urutan_langkah IS NOT NULL')->order_by('urutan_langkah','ASC')->get();
        $data['data'] = $getData;
        $data['save_method'] = 'save';
        $data['listMandatoryStatus'] = $this->trkelompok_perizinan->listMandatoryStatus;
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Edit Kelompok Perizinan";
        $this->template->build('edit', $this->session_info);
    }

    /**
     * @author Indra
     * Fungsi untuk menyimpan data dari form add maupun edit
     * Save tidak pakai datamapper karena data tidak berhasil disimpan. Walaupun  jika dijalankan save berhasil
     */
    public function save(){
        $newData = true;
        $idKelompokIzin = null;

        $this->trkelompok_perizinan = new trkelompok_perizinan();
        if($this->input->post('id')){
            $idKelompokIzin = $this->input->post('id');
            $newData = false;
        }

        $namaKelompok = $this->input->post('n_kelompok');
        $data = array(
            'n_kelompok' => $this->input->post('n_kelompok')
        );

        ## BEGIN - Susun Data Langkah Perizinan##
        $dataLangkah = array();
        if(!empty($_POST['langkah_perizinan'])){
            $no = 1;
            foreach($_POST['langkah_perizinan'] as $indexLangkah=>$postLangkah){
                if(isset($postLangkah['trstspermohonan_id']) && $postLangkah['trstspermohonan_id'] != ''){
                    $dataLangkah[$indexLangkah]['trstspermohonan_id'] = (int)$postLangkah['trstspermohonan_id'];
                    $dataLangkah[$indexLangkah]['trkelompok_perizinan_id'] = (int)$idKelompokIzin;
                    $dataLangkah[$indexLangkah]['urut'] = $no++;
                }
            }
        }
        ## END - Susun Data Langkah Perizinan##

        if($newData){//Jika Kelompok Izin Baru
            $saveKelompokIzinData = $this->trkelompok_perizinan->insertKelompokPerizinan($data, $dataLangkah);
        }else{
            $saveKelompokIzinData = $this->trkelompok_perizinan->updateKelompokPerizinan($idKelompokIzin, $data,  $dataLangkah);
        }

        if(!$saveKelompokIzinData){
            $this->session->set_flashdata('flash_message', array('message' => 'Gagal menyimpan data Kelompok Perizinan. Silahkan coba lagi','class' => 'error'));
        }else{
            $this->session->set_flashdata('flash_message', array('message' => 'Berhasil menyimpan data Kelompok Perizinan','class' => 'success'));
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Kelompok Perizinan','Save Kelompok Perizinan " . $namaKelompok . "','" . $tgl . "','" . $u_ser . "')");

            redirect('kelompok_perizinan');
        }
    }

    public function delete($idKelompokIzin){
        $getData = $this->trkelompok_perizinan->get_by_id($idKelompokIzin);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke index
            $this->session->set_flashdata('flash_message', array('message' => 'Kelompok Izin tidak valid','class' => 'error'));
            redirect('kelompok_perizinan');
        }else{
            if($getData->trkelompok_perizinan->trperizinan->id){//Jika ada Izin yang termasuk ke Kelompok Izin tersebut
                $this->session->set_flashdata('flash_message', array('message' => 'Kelompok Izin tidak dapat dihapus karena ada Izin yang termasuk kelompok tersebut','class' => 'error'));
                redirect('kelompok_perizinan');
            }

            //Hapus Langkah Perizinan
            $this->trkelompok_perizinan->deleteLangkahPerizinan($idKelompokIzin);

            if($this->trkelompok_perizinan->deleteKelompokPerizinan($idKelompokIzin)){//Jika berhasil delete
                $this->session->set_flashdata('flash_message', array('message' => 'Kelompok Izin berhasil dihapus','class' => 'success'));
                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $p = $this->db->query("call log ('Kelompok Perizinan','Delete Kelompok Izin " . $this->trkelompok_perizinan->n_kelompok. "','" . $tgl . "','" . $u_ser . "')");
            }else{
                $this->session->set_flashdata('flash_message', array('message' => 'Terjadi kesalahan saat menghapus Kelompok Izin. Silahkan coba lagi','class' => 'error'));
            }
            redirect('kelompok_perizinan');
        }
    }

}
?>