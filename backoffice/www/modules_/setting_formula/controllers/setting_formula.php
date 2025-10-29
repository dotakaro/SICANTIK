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

class Setting_formula extends WRC_AdminCont {
    public function __construct() {
        parent::__construct();
        $this->load->model('setting_formula_retribusi');
        $this->setting_formula_retribusi = new setting_formula_retribusi();

        $this->load->model('setting_formula_detail');
        $this->setting_formula_detail = new setting_formula_detail();
    }
    
    /*private function _check_auth(){
        $enabled = FALSE;//enable hak akses
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }
    }*/

    public function index() {
        $this->load->model('perizinan/trperizinan');
        $this->trperizinan = new trperizinan();
        
        $data['list'] = $this->trperizinan->where_in_related_trkelompok_perizinan('id',$this->__get_izin_dengan_tarif())->get();
        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#setting_formula').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Setting Formula";
        $this->template->build('index', $this->session_info);
    }
    
    public function add($trperizinan_id = null){
        $this->load->model('perizinan/trperizinan');
        $this->trperizinan = new trperizinan();
        $perizinan = $this->trperizinan->get_by_id($trperizinan_id);
        if(!$perizinan->id || $trperizinan_id == ''){
            redirect('setting_formula');
        }

        $data['id'] = "";
        $data['trperizinan_id']  = $trperizinan_id;
        $data['setting_formula_detail'] = array(
            0=>array('id'=>null, 'trunitkerja_id'=>null),
//            1=>array('id'=>null, 'trunitkerja_id'=>null)
        ); //Dummy Data agar muncul 2 dropdown unit kerja
        $data['save_method'] = "save";
        $data['perizinan'] = $perizinan;

        $this->load->model('setting_tarif/setting_tarif_item');
        $this->setting_tarif_item = new setting_tarif_item();
        $data['setting_tarif_item'] = $this->setting_tarif_item->where('trperizinan_id',$trperizinan_id)
            ->where('deleted',0)->order_by('nama_item','ASC')->get();

        ## Load Daftar Unit Kerja ##
        $this->load->model('unitkerja/trunitkerja');
        $this->trunitkerja = new trunitkerja();
        $all_unit_kerja = $this->trunitkerja->order_by('n_unitkerja','ASC')->get();
        $option_unit_kerja = array();
        $option_unit_kerja[null] = 'Pilih salah satu';
        foreach($all_unit_kerja as $unit_kerja){
            $option_unit_kerja[$unit_kerja->id] = $unit_kerja->n_unitkerja;
        }
        $data['list_unit_kerja'] = $option_unit_kerja;
        ############################

        $js = "
                $(document).ready(function() {
                    $('#form').validate({
                        rules:{
                            formula:{
                                    required:true,
                            }
                        },
                        messages:{
                            formula:{
                                    required:\"Formula harus diisi\"
                            }
                        }
                    });
                } );
            ";

        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Formula";
        $this->template->build('add', $this->session_info);
    }
	
/*
     * edit is a method to show page for updating data
     */
    public function edit($item_id){
        $this->load->model('setting_formula_retribusi');
        $this->setting_formula_retribusi = new setting_formula_retribusi();

        $existing_data = $this->setting_formula_retribusi->where('id',$item_id)->get();
        $data['id'] = $existing_data->id;
        $data['formula'] = $existing_data->formula;
        $data['trperizinan_id']  = $existing_data->trperizinan_id;

        $setting_formula_detail = array(
            0=>array('id'=>null, 'trunitkerja_id'=>null),
//            1=>array('id'=>null, 'trunitkerja_id'=>null)
        ); //Dummy Data agar muncul 2 dropdown unit kerja

        $existing_detail = $existing_data->setting_formula_detail->where('setting_formula_retribusi_id',$item_id)->get();
        if($existing_detail->id){
            foreach($existing_detail as $key=>$existing){
                //echo $key." ";echo $existing->id." ";echo $existing->trunitkerja_id."<br>";
                $setting_formula_detail[$key]['id'] = $existing->id;
                $setting_formula_detail[$key]['trunitkerja_id'] = $existing->trunitkerja_id;
            }
        }
        $data['setting_formula_detail'] = $setting_formula_detail;
        $data['save_method'] = "save";

        $this->load->model('perizinan/trperizinan');
        $this->trperizinan = new trperizinan();
        $perizinan = $this->trperizinan->get_by_id($existing_data->trperizinan_id);
        $data['perizinan'] = $perizinan;

        $this->load->model('setting_tarif/setting_tarif_item');
        $this->setting_tarif_item = new setting_tarif_item();
        $data['setting_tarif_item'] = $this->setting_tarif_item->where('trperizinan_id',$existing_data->trperizinan_id)
            ->where('deleted',0)->order_by('nama_item','ASC')->get();

        ## Load Daftar Unit Kerja ##
        $this->load->model('unitkerja/trunitkerja');
        $this->trunitkerja = new trunitkerja();
        $all_unit_kerja = $this->trunitkerja->order_by('n_unitkerja','ASC')->get();
        $option_unit_kerja = array();
        $option_unit_kerja[null] = 'Pilih salah satu';
        foreach($all_unit_kerja as $unit_kerja){
            $option_unit_kerja[$unit_kerja->id] = $unit_kerja->n_unitkerja;
        }
        $data['list_unit_kerja'] = $option_unit_kerja;
        ############################

        $js = "
                $(document).ready(function() {
                    $('#form').validate({
                        rules:{
                            formula:{
                                    required:true,
                            }
                        },
                        messages:{
                            formula:{
                                    required:\"Formula harus diisi\"
                            }
                        }
                    });
                } );
            ";
        
        $this->template->set_metadata_javascript($js);
        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Formula";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $this->load->model('setting_formula_retribusi');
        $this->setting_formula_retribusi = new setting_formula_retribusi();

        $id = $this->input->post('id');
        $formula = strtolower($this->_remove_whitespace($this->input->post('formula')));
        $trperizinan_id = $this->input->post('trperizinan_id');
        $trunitkerja_id = $this->input->post('trunitkerja_id');
        $this->setting_formula_retribusi->id = $id;
        $this->setting_formula_retribusi->formula = $formula;
        $this->setting_formula_retribusi->trperizinan_id = $trperizinan_id;
        $this->setting_formula_retribusi->trunitkerja_id = $trunitkerja_id;
        
        if(! $this->setting_formula_retribusi->save()) {
            echo '<p>' . $this->setting_formula_retribusi->error->string . '</p>';
        } else {
            $id = $this->setting_formula_retribusi->id;
            if(isset($_POST['SettingFormulaDetail']) && !empty($_POST['SettingFormulaDetail'])){
                //Hapus jika sudah ada detail sebelumnya
//                $setting_formula_detail = new setting_formula_detail();
//                $setting_formula_detail->where_related('setting_formula_retribusi','id',$id)->get()->delete_all();
                foreach($_POST['SettingFormulaDetail'] as $key=>$formula_detail){
                    $setting_formula_detail = new setting_formula_detail();
                    if(isset($formula_detail['id']) && !empty($formula_detail['id'])){
                        $setting_formula_detail->id = $formula_detail['id'];
                    }
                    $setting_formula_detail->setting_formula_retribusi_id = $id;
                    $setting_formula_detail->trunitkerja_id = $formula_detail['trunitkerja_id'];
                    $setting_formula_detail->save($this->setting_formula_retribusi);
                }

            }
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Formula','Save Setting Formula Retribusi".$formula."','".$tgl."','".$u_ser."')");
            redirect('setting_formula');
        }
    }

    /**
     * @author Indra Halim
     * Fungsi untuk menghapus detail Unit Kerja dari daftar unit yang boleh menghitung retribusi suatu Izin
     */
    public function delete_detail(){
        $detailId = $this->input->post('id_detail');
        $ret = array();
        $success  = false;
        $message = '';
        $setting_formula_detail = new setting_formula_detail();
        $setting_formula_detail->where('id', $detailId)->get();
        if($setting_formula_detail->delete()){
            $success = true;
        }else{
            $message = 'Tidak dapat menghapus Detail. Silahkan coba lagi';
        }
        $ret['success'] = $success;
        $ret['message'] = $message;
        echo json_encode($ret);
    }
	
    private function _remove_whitespace($string){
         return preg_replace("/\s+/", "",$string );
    }
}
?>