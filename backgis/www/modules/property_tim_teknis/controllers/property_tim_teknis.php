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

class Property_tim_teknis extends WRC_AdminCont {
    public function __construct() {
        parent::__construct();
	    $this->load->model('property_teknis_header');
        $this->property_teknis_header = new property_teknis_header();

        $this->load->model('property_teknis_detail');
        $this->property_teknis_detail = new property_teknis_detail();
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
//        $this->_check_auth();
        $this->load->model('perizinan/trperizinan');
        $this->trperizinan = new trperizinan();
        $query = "
            SELECT u.id as trunitkerja_id, u.n_unitkerja, i.id as trperizinan_id, i.n_perizinan, ph.id AS property_teknis_header_id
            FROM trunitkerja u
            INNER JOIN trperizinan_trunitkerja pu ON
                ( pu.trunitkerja_id = u.id AND pu.relation_type='A' )
            INNER JOIN trperizinan i ON i.id = pu.trperizinan_id
            LEFT JOIN property_teknis_header ph ON
                (ph.trunitkerja_id = u.id
                AND ph.trperizinan_id = pu.trperizinan_id
                AND ph.trunitkerja_id = pu.trunitkerja_id)
        ";
        $getData = $this->db->query($query)->result();
//        $data['list'] = $this->trperizinan->get();
        $data['list'] = $getData;

        $this->load->vars($data);
        $js =  "
            function confirm_link(text){
                    if(confirm(text)){ return true;
                    }else{ return false; }
                }
                $(document).ready(function() {
                        oTable = $('#property_tim_teknis').dataTable({
                                \"bJQueryUI\": true,
                                \"sPaginationType\": \"full_numbers\"
                        });

                });";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Property Tim Teknis";
        $this->template->build('index', $this->session_info);
    }
    
    public function add($trperizinan_id, $trunitkerja_id){
        $this->load->model('perizinan/trperizinan');
        $this->load->model('property/trproperty');
        $this->load->model('unitkerja/trunitkerja');

        $this->trperizinan = new trperizinan();
        $this->trproperty = new trproperty();
        $this->trunitkerja = new trunitkerja();
        $unitKerja = new trunitkerja();

        $data['save_method'] = "save";
        $data['id'] = "";
        
        $perizinan = $this->trperizinan->get_by_id($trperizinan_id);
        $getUnitKerja = $unitKerja->get_by_id($trunitkerja_id);
        $data['perizinan'] = $perizinan;
        $data['unitKerja'] = $getUnitKerja;

//        $parent_property = $this->trproperty->where('c_type',2)->get();
        $parent_property = $this->trproperty->where('c_type',2)->where_in_related('trperizinan','id',$perizinan->id)->get();
        $data['list_parent_property'] = $parent_property;

        $all_unit_kerja = $this->trunitkerja->order_by('n_unitkerja','ASC')->get();
        $option_unit_kerja = array();
        $option_unit_kerja[null] = 'Pilih salah satu';
        foreach($all_unit_kerja as $unit_kerja){
            $option_unit_kerja[$unit_kerja->id] = $unit_kerja->n_unitkerja;
        }
        $data['list_unit_kerja'] = $option_unit_kerja;

        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Property Tim Teknis";
        $this->template->build('add', $this->session_info);
    }
	
    /*
     * edit is a method to show page for updating data
     */
    public function edit($item_id){
        $this->load->model('perizinan/trperizinan');
        $this->load->model('property/trproperty');
        $this->load->model('unitkerja/trunitkerja');

        $this->trperizinan = new trperizinan();
        $this->trproperty = new trproperty();
        $this->trunitkerja = new trunitkerja();

        $data['save_method'] = "save";
        $data['id'] = $item_id;

        $property_teknis_header = $this->property_teknis_header->get_by_id($item_id);
        $data['property_teknis_header'] = $property_teknis_header;

        //Load data property detail sebelumnya dan susun dalam array
        $existing_detail = array();
        foreach($property_teknis_header->property_teknis_detail->get() as $property_teknis_detail){
            $existing_detail[$property_teknis_detail->trproperty_id] = array(
                'trunitkerja_id'=>$property_teknis_detail->trunitkerja_id,
                'id'=>$property_teknis_detail->id,
                'trproperty_id'=>$property_teknis_detail->trproperty_id,
            );
        }
        $data['existing_detail'] = $existing_detail;

        $data['perizinan'] = $property_teknis_header->trperizinan->get();
        $data['unit_kerja'] = $property_teknis_header->trunitkerja->get();

//        $parent_property = $this->trproperty->where('c_type',2)->get();
        $perizinan = $property_teknis_header->trperizinan->get();
//        $data['perizinan'] = $perizinan;
        $parent_property = $this->trproperty->where('c_type',2)->where_in_related('trperizinan','id',$perizinan->id)->get();

        $data['list_parent_property'] = $parent_property;

        $all_unit_kerja = $this->trunitkerja->order_by('n_unitkerja','ASC')->get();
        $option_unit_kerja = array();
        $option_unit_kerja[null] = 'Pilih salah satu';
        foreach($all_unit_kerja as $unit_kerja){
            $option_unit_kerja[$unit_kerja->id] = $unit_kerja->n_unitkerja;
        }
        $data['list_unit_kerja'] = $option_unit_kerja;

        $this->load->vars($data);
        $this->session_info['page_name'] = "Setting Property Tim Teknis";
        $this->template->build('edit', $this->session_info);
    }

    /*
     * Save and update for manipulating data.
     */
    public function save() {
        $id = $this->input->post('id');
        $trperizinan_id = $this->input->post('trperizinan_id');
        $trunitkerja_id = $this->input->post('trunitkerja_id');
        $this->property_teknis_header->id = $id;
        $this->property_teknis_header->trperizinan_id = $trperizinan_id;
        $this->property_teknis_header->trunitkerja_id = $trunitkerja_id;

        if(! $this->property_teknis_header->save()) {
            echo '<p>' . $this->property_teknis_header->error->string . '</p>';
        } else {
            if(isset($_POST['PropertyTeknisDetail']) && !empty($_POST['PropertyTeknisDetail'])){
                foreach($_POST['PropertyTeknisDetail'] as $detail){
                    $property_teknis_detail = new property_teknis_detail();
                    if(isset($detail['id']) && !empty($detail['id'])){
                        $property_teknis_detail->id = $detail['id'];
                    }
                    $property_teknis_detail->trproperty_id = $detail['trproperty_id'];
                    $property_teknis_detail->trunitkerja_id = $detail['trunitkerja_id'];
                    $property_teknis_detail->save($this->property_teknis_header);
                }
            }
            
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Property Tim Teknis','Setting Property Tim Teknis".$trperizinan_id."','".$tgl."','".$u_ser."')");
            redirect('property_tim_teknis');
        }
    }

}
?>