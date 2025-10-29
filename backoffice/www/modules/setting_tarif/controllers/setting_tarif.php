<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of perizinan class
 * Class untuk Report Component
 * @author  Indra Halim
 * @since   1.0
 *
 */

class Setting_tarif extends WRC_AdminCont {
    public function __construct() {
        parent::__construct();
	    $this->load->model('setting_tarif_item');
        $this->setting_tarif_item = new setting_tarif_item();
        
        $this->load->model('perizinan/trperizinan');
        $this->trperizinan = new trperizinan();
    }
    
    /*private function _check_auth(){
        $enabled = FALSE;//enable hak akses
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
                $this->retribusi = new trretribusi();
                $this->perizinan = new trperizinan();
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }
    }*/

    public function index() {
//        $this->_check_auth();
        
        $data['list'] = $this->setting_tarif_item->where('deleted',0)->get();
        $data['ket_exist'] = NULL;
        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#setting_tarif').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Tarif";
        $this->template->build('index', $this->session_info);
    }
    
    public function add(){
        $data['nama_item'] = "";
        $data['satuan'] = "";
        $data['trperizinan_id']  = "";
        $data['save_method'] = "save";
        $data['id'] = "";
        
        $js = "
                $(document).ready(function() {
                    $('#form').validate({
                        rules:{
                                nama_item:{
                                        required:true,
                                },
                                trperizinan_id:{
                                        required:true,
                                }
                        },
                        messages:{
                                nama_item:{
                                        required:\"Nama Item harus diisi\"
                                },
                                trperizinan_id:{
                                        required:\"Jenis Perizinan harus diisi\"
                                }
                        }
                    });
                    $(\"#tabs\").tabs();
                } );
            ";
        
        $list_izin = $this->trperizinan->where_in_related_trkelompok_perizinan('id',$this->__get_izin_dengan_tarif())->get();
        $option_izin = array();
        $option_izin[null] = 'Pilih Izin';
        foreach($list_izin as $izin){
            $option_izin[$izin->id] = $izin->n_perizinan;
        }
        $data['option_izin'] = $option_izin;
        
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Tarif";
        $this->template->build('add', $this->session_info);
    }
	
/*
     * edit is a method to show page for updating data
     */
    public function edit($item_id){
        $existing_data = $this->setting_tarif_item->where('id',$item_id)->get();
        $data['nama_item'] = $existing_data->nama_item;
        $data['satuan'] = $existing_data->satuan;
        $data['trperizinan_id']  = $existing_data->trperizinan_id;
        $data['save_method'] = "save";
        $data['id'] = $existing_data->id;
        
        $setting_tarif_harga = new setting_tarif_harga();
        $data['existing_tarif_kategori'] = $setting_tarif_harga->where_related('setting_tarif_item',$existing_data)->order_by('kategori','ASC')->get();
        
        $js = "
                $(document).ready(function() {
                    $('#form').validate({
                        rules:{
                                nama_item:{
                                        required:true,
                                },
                                trperizinan_id:{
                                        required:true,
                                }
                        },
                        messages:{
                                nama_item:{
                                        required:\"Nama Item harus diisi\"
                                },
                                trperizinan_id:{
                                        required:\"Jenis Perizinan harus diisi\"
                                }
                        }
                    });
                    $(\"#tabs\").tabs();
                } );
            ";
        
        $list_izin = $this->trperizinan->where_in_related_trkelompok_perizinan('id',$this->__get_izin_dengan_tarif())->get();
        $option_izin = array();
        $option_izin[null] = 'Pilih Izin';
        foreach($list_izin as $izin){
            $option_izin[$izin->id] = $izin->n_perizinan;
        }
        $data['option_izin'] = $option_izin;
        
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Tambah Tarif";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $id = $this->input->post('id');
        $nama_item = $this->input->post('nama_item');
        $satuan = $this->input->post('satuan');
        $trperizinan_id = $this->input->post('trperizinan_id');
        $this->setting_tarif_item->id = $id;
        $this->setting_tarif_item->nama_item = $nama_item;
        $this->setting_tarif_item->satuan = $satuan;
        $this->setting_tarif_item->trperizinan_id = $trperizinan_id;
        
        if(! $this->setting_tarif_item->save()) {
            echo '<p>' . $this->setting_tarif_item->error->string . '</p>';
        } else {
            if(isset($_POST['SettingTarifHarga']) && !empty($_POST['SettingTarifHarga'])){
                foreach($_POST['SettingTarifHarga'] as $tarif_harga){
                    $setting_tarif_harga = new setting_tarif_harga();
                    if(isset($tarif_harga['id']) && !empty($tarif_harga['id'])){
                        $setting_tarif_harga->id = $tarif_harga['id'];
                    }
                    $setting_tarif_harga->kategori = $tarif_harga['kategori'];
                    $setting_tarif_harga->harga = $tarif_harga['harga'];
                    $setting_tarif_harga->save($this->setting_tarif_item);
                }
            }
            
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Tarif','Insert tarif ".$nama_item."','".$tgl."','".$u_ser."')");
            redirect('setting_tarif');
        }
    }

    public function delete($id = NULL) {
        $setting_tarif_item = $this->setting_tarif_item->where('id', $id)->get();
        $setting_tarif_item->deleted = 1;

        $tgl = date("Y-m-d H:i:s");
        $u_ser = $this->session->userdata('username');
        $p = $this->db->query("call log ('Setting Tarif','Delete Item Tarif ".$setting_tarif_item->nama_item."','".$tgl."','".$u_ser."')");

        if($setting_tarif_item->save()) {
            redirect('setting_tarif');
        }else{
            echo '<p>' . $setting_tarif_item->error->string . '</p>';
        }
    }
    
    public function delete_kategori(){
        $setting_tarif_harga_id = $this->input->post('id_kategori');
        $ret = array();
        $success  = false;
        $message = '';
        $setting_tarif_harga = new setting_tarif_harga();
        $setting_tarif_harga->where('id',$setting_tarif_harga_id)->get();
        if($setting_tarif_harga->delete()){
            $success = true;
        }else{
            $message = 'Tidak dapat menghapus Kategori. Silahkan coba lagi';
        }
        $ret['success'] = $success;
        $ret['message'] = $message;
        echo json_encode($ret);
    }
	
    private function _remove_whitespace($string){
            return preg_replace("/\s+/", " ",$string );
    }

}
?>