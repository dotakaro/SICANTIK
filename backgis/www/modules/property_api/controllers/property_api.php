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

class Property_api extends WRC_AdminCont {

    public function __construct() {
        parent::__construct();
        $this->load->model('property_hierarchy');
        $this->load->model('mapping');
        $this->load->model('property_load');
        $this->load->model('trapi');
    }

    /*private function _check_auth(){
        $enabled = FALSE;//enable hak akses
        $list_auths = $this->session_info['app_list_auth'];

        if(!$enabled) {
            redirect('dashboard');
        }
    }*/

    public function index() {
//        $this->_check_auth();
        //$this->load->model('property/trproperty');
        //$this->load->model('perizinan/trperizinan');
        $this->perizinan = new trperizinan();

        //$data['list'] = $this->trapi->get();

        $this->load->vars($data);

        $js =  "function confirm_link(text){
                   if(confirm(text)){ return true;
                   }else{ return false; }
               }
               $(document).ready(function() {
                       oTable = $('#listApi').dataTable({
                               \"bJQueryUI\": true,
                               \"sPaginationType\": \"full_numbers\"
                       });
               } );
               ";

        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Data API";
        $this->template->build('index', $this->session_info);
    }

    public function add(){
        //$data['perizinan'] = $perizinan;
        $data['list_type'] = array(
            '-1'=>'-pilih-',
            'xml'=>'xml',
            'json'=>'json'
        );
        $data['save_method'] = 'save';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Add API";
        $this->template->build('add', $this->session_info);
    }

    public function edit($propertyApiId){
        $this->load->helper('tree');
        $dataPropertyHierarchy = array();

        $getData = $this->trapi->get_by_id($propertyApiId);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke index
            redirect('property_api');
        }
        $structureData = $this->property_hierarchy->where('trapi_id',$propertyApiId)->get();
        $data['data'] = $getData;
        $data['dataPropertyHierarchy'] = $structureData;
        $data['list_type'] = array(
            '-1'=>'-pilih-',
            'xml'=>'xml',
            'json'=>'json'
        );
        $data['save_method'] = 'save';
        $this->load->vars($data);
        $js ="";
        $this->template->set_metadata_javascript($js);
        $this->session_info['page_name'] = "Edit API";
        $this->template->build('edit', $this->session_info);
    }

    public function save(){
        $this->load->model('mapping_detail');
        $trApi = new trapi();
        $trApiId = null;
//        echo "<pre>";print_r($_POST);exit();
        $newData = true;
        if($this->input->post('id')){
            $trApiId = $this->input->post('id');
            $trApi = $this->trapi->get_by_id($trApiId);
            $this->trapi->id = $this->input->post('id');
            $newData = false;
        }
        $apiUrl = $this->input->post('api_url');
        $shortDesc = $this->input->post('short_desc');
        $dataType = $this->input->post('data_type');
        $rootLevel = $this->input->post('root_level');

        $trApi->api_url = $apiUrl;
        $trApi->short_desc = $shortDesc;
        $trApi->data_type = $dataType;
        $trApi->root_level = $rootLevel;
        $saveTrApi = $trApi->save();

        $trApi = $this->trapi->get_by_id($trApiId);

        if($saveTrApi){
            ##BEGIN - Menyimpan Data Mapping ##
            //Hapus Tabel Mapping Detail
            $delMappingDetail = new mapping_detail();
            $delMappingDetail->where_related('mapping/trapi','id', $this->trapi->id)->get();
            $delMappingDetail->delete_all();

            //Hapus Tabel Mapping
            $delMapping = new mapping();
            $delMapping->where_related('trapi','id', $this->trapi->id)->get();
            $delMapping->delete_all();
    //        exit();

            if(isset($_POST['mapping']) && !empty($_POST['mapping'])){
                $mappings = $this->input->post('mapping');
                if(!empty($mappings)){
                    foreach($mappings as $mapping){
                        $objMapping = new mapping();
                        $objMapping->table_name = $mapping['table_name'];
                        $saveMapping = $objMapping->save($trApi);
                        if($saveMapping){
                            if(isset($mapping['detail']) && !empty($mapping['detail'])){
                                foreach($mapping['detail'] as $detail){
                                    $mapping_detail = new mapping_detail();
                                    $mapping_detail->field_table = $detail['field_table'];
                                    $mapping_detail->field_api = $detail['field_api'];
                                    $mapping_detail->save($objMapping);
                                }
                            }
                        }
                    }
                }
            }
        }
        ## END  - Menyimpan Data Mapping ##

        if(!$saveTrApi){
            echo '<p>' . $this->user->error->string . '</p>';
        }else{
            $tgl = date("Y-m-d H:i:s");
            $u_ser = $this->session->userdata('username');
            $p = $this->db->query("call log ('Setting Api','Insert API " . $this->trapi->short_desc . "','" . $tgl . "','" . $u_ser . "')");
            if($newData){//Jika data baru, redirect ke Edit
                redirect('property_api/edit/'.$this->trapi->id);
            }else{//Jika data lama, redirect ke Index
                redirect('property_api');
            }
        }
    }

    public function delete($propertyApiId){
        $getData = $this->trapi->get_by_id($propertyApiId);
        if(!$getData->id){//Jika data tidak ditemukan, redirect ke index
            redirect('property_api');
        }else{
            $shortDesc = $this->trapi->short_desc;
            $getProperty = $this->property_hierarchy->where('trapi_id', $propertyApiId)->get();
            if($getData->delete()){//Jika berhasil delete
                $getProperty->delete_all();

                $tgl = date("Y-m-d H:i:s");
                $u_ser = $this->session->userdata('username');
                $p = $this->db->query("call log ('Setting API','Delete API " . $shortDesc . "','" . $tgl . "','" . $u_ser . "')");
            }
            redirect('property_api');
        }
    }

    public function get_structure(){
        $return = array();
        $success = false;
//        $data = array();
        $apiUrl = $this->input->post('api_url');
        $dataType = $this->input->post('data_type');
        $rootLevel = $this->input->post('root_level');
        $trApiId = $this->input->post('api_id');
        if($apiUrl && $dataType){//Jika URL dan Tipe Data ada, mulai load
            $web_service_data = $this->property_hierarchy->getWebServiceData($apiUrl, $dataType);
            if(!empty($web_service_data)){
                if($this->property_load->emptyStructure($trApiId)){
                    $this->property_load->setTrApiId($trApiId);
//                    echo "<pre>";print_r($web_service_data);
                    $success = $this->property_load->parseWebServiceArray($web_service_data, 0, $rootLevel);
                }
            }
        }
        $return['success'] = $success;
//        $return['data'] = $data;
        echo json_encode($return);exit();
    }

    public function get_html_tree($trApiId){
        $html = '';
        $this->load->helper('tree');
        $structureData = $this->property_hierarchy->where('trapi_id',$trApiId)->get();
        if($structureData->id){//Jika data ditemukan
            foreach($structureData as $key=>$value){
                $data[$key]['id'] = $value->id;
                $data[$key]['data_key'] = $value->data_key;
                $data[$key]['data_value'] = $value->data_value;
                $data[$key]['parent_id'] = $value->parent_id;
                $data[$key]['trapi_id'] = $value->trapi_id;
            }
            $data = build_tree($data);
            $html = olLiTree($data);
        }
        echo $html;
    }

    public function getServiceData(){
        $return = array();
        $level = 0;
        $data = array();
        $getApi = $this->trapi->order_by('id','desc')->get();
        if($getApi->id) {//Jika URL dan Tipe Data ada, mulai load
            $level = $getApi->root_level;
            $data = $this->property_hierarchy->getWebServiceData($getApi->api_url, $getApi->data_type);
        }
        $return['data'] = $data;
        $return['level'] = $level;
        echo json_encode($return);exit();
    }

    /*public function get_data($type='xml'){
        $web_service_data = $this->property_hierarchy->get_data($url, $type);
        if(!empty($web_service_data)){
            $this->property_load->parse_web_service_array($web_service_data);
            echo "<pre>";print_r($web_service_data);exit();
        }
    }*/

    public function combo_grid_field_api($trApiId){
        $this->load->library('MY_Input');
        $total_pages=0;
        $page=$this->input->get('page');
        $limit =$this->input->get('rows'); // get how many rows we want to have into the grid
        $sidx =$this->input->get('sidx'); // get index row - i.e. user click to sort
        $sord =$this->input->get('sord'); // get the direction

        if($this->input->get('searchTerm')){
            $searchTerm = $this->input->get('searchTerm');
        }else{
            $searchTerm = "";
        }
        if(!$sidx) $sidx ='report_code';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        }

        $this->db->select('MAX(level) as last_level');
        $this->db->where('trapi_id', $trApiId);
        $queryLastLevel = $this->db->get('property_hierarchy')->result();
        $lastLevel = $queryLastLevel[0]->last_level;

        $this->db->where("LOWER(data_key) LIKE '%{$searchTerm}%'");
        $this->db->where('trapi_id', $trApiId);
        $this->db->where("level",$lastLevel);
        $this->db->from('property_hierarchy');
        $count=$this->db->count_all_results();

        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)

        $response=new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;
        $i=0;

        if($total_pages!=0){

            $this->db->select('data_key');
//            $this->db->where("LOWER(data_key) LIKE '%{$searchTerm}%'");
            $this->db->where('trapi_id', $trApiId);
            $this->db->where("level",$lastLevel);
//            $this->db->order_by($sidx,$sord);
            $query=$this->db->get('property_hierarchy',$limit,$start);

            if(count($query->result())>0){
                foreach ($query->result() as $result)
                {
                    $response->rows[$i]['data_key']=$result->data_key;
                    $i++;
                }
            }else{
                $response->rows[$i]['data_key']="";
            }
        }

        echo json_encode($response);
        exit();
    }

}
?>