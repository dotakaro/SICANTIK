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

class Report_component extends WRC_AdminCont {

	private $_option_report_type=array();
	private $_jasper_folder='';

    public function __construct() {
        parent::__construct();
	
//        $this->perizinan = new trperizinan();
		$this->load->model('Report_component_model');
		$this->report_component_model=new Report_component_model();
		
		$option_report_types=$this->Report_component_model->get_report_types();
		$option_report_types=array('-1'=>'-Please Select-')+$option_report_types;
		$this->_option_report_type=$option_report_types;
		$this->_jasper_folder=realpath(BASEPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."jasper");


        /*$enabled = FALSE;
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '1') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }*/
    }

    public function index() {

//        $data['list'] = $this->perizinan->get();
//        $data['list_izin'] = $this->perizinan->get_list();
		if(!empty($_POST)){
			//Jika form sudah dipost, tentukan apakah action tersebut merupakan create atau open
			$clicked_button=$this->input->post('clicked_button');
			$report_code=$this->input->post('report_component_code');
			
			if($clicked_button=='btn_create'){
				redirect('report_component/add/'.$report_code);
			}elseif($clicked_button=='btn_open'){
				$report_id=$this->input->post('report_id');
				redirect('report_component/edit/'.$report_id);
			}elseif($clicked_button=='btn_copy'){
				$report_id=$this->input->post('report_id');
				redirect('report_component/copy/'.$report_id);
			}else{
				redirect('report_component');
			}
		}
        $this->session_info['page_name'] = "Report Component";
        $this->template->build('index', $this->session_info);
    }
	
	public function add($report_component_code=NULL){
        $data = $this->_prepareForm();
		if(!is_null($report_component_code)){
			$data['report_component_code']=$report_component_code;
		}else{
			$data['report_component_code']='';
		}
		

		$this->load->vars($data);
		$js="";
        $this->template->set_metadata_javascript($js);		
		$this->session_info['page_name'] = "Add Report Component";
		$this->template->build('add', $this->session_info);
	}
	
	public function edit($report_id=null){
        $data = $this->_prepareForm();
		$data['report_component_data']=$this->Report_component_model->get_data($report_id);
		/*$data['option_perizinan']=array('-1'=>'-Please Select-')+$this->Report_component_model->get_perizinan_list();
		$data['option_report_type']=$this->_option_report_type;*/
		$this->load->vars($data);
		$js="";
        $this->Report_component_model->get_report_component('BAP',155, 1302);

        $this->template->set_metadata_javascript($js);		
		$this->session_info['page_name'] = "Edit Report Component";
		$this->template->build('edit', $this->session_info);
	}

    private function _prepareForm(){
        $data = array();
        $opsiUnitKerja = array(-1=>'-pilih salah satu-');
        $this->load->model('unitkerja/trunitkerja');
        $this->unitkerja = new trunitkerja();
        $getUnitKerja = $this->unitkerja->select('id, n_unitkerja')->order_by('n_unitkerja','ASC')->get();
        if($getUnitKerja->id){
            foreach($getUnitKerja as $unitKerja){
                $opsiUnitKerja[$unitKerja->id] = $unitKerja->n_unitkerja;
            }
        }
        $data['option_perizinan']=array('-1'=>'-Please Select-')+$this->Report_component_model->get_perizinan_list();
        $data['option_report_type']=$this->_option_report_type;
        $data['opsiUnitKerja'] = $opsiUnitKerja;
        return $data;
    }
	
	public function copy($report_component_id=null){
		//baca table report_component
		$data['report_component_data']=$this->Report_component_model->get_data($report_component_id,'array');
		unset($data['report_component_data']['id']);//hapus idnya agar saat menyimpan ke tabel report_generator akan diinsert sebagai record baru
		$data['report_component_data']['report_component_code'].='-Copy';
		
		$response=$this->Report_component_model->save($data['report_component_data']);
		if($response['success']==true){
			$report_component_id=$response['id'];
			redirect('report_component/edit/'.$report_component_id);
		}else{
			echo "Oops... terjadi kesalahan ketika menduplikasi komponen report ini. Silahkan coba kembali.";
		}
	}
	
	public function delete(){
		if(!empty($_POST['report_component_id'])){
			$report_component_id = $this->input->post('report_component_id');
			echo $this->Report_component_model->delete($report_component_id);
		}else{
			echo false;
		}
	}
	
	private function _remove_whitespace($string){
		return preg_replace("/\s+/", " ",$string );
	}
	
	public function save_mainform(){
		if(!empty($_POST)){

			$response=array();
			$data=$_POST['data'];
			
			//Cek apakah sudah ada settting untuk report ini atau belum
			if(!isset($data['ReportComponent']['id'])){
				$data['ReportComponent']['id']='';
			}
			$check_duplicate = $this->Report_component_model->no_report_setting_exists($data['ReportComponent']['report_type'],$data['ReportComponent']['trperizinan_id'],$data['ReportComponent']['id'], $data['ReportComponent']['trunitkerja_id']);
			
			if($check_duplicate['status']==true){
				$response=$this->Report_component_model->save($data['ReportComponent']);
				if($response['success']==true){//Jika sukses save ke table report_components
					//save ke tabel report_group_datas
					$report_component_id=$response['id'];
				}
			}else{
				$response['success']=false;
				$response['message']=$check_duplicate['message'];
			}
			echo json_encode($response);exit();
		}
	}
	
	
	
	public function combo_grid_report_component(){
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
        if(!$sidx) $sidx ='rc.report_component_code';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        }

		$this->db->where("(LOWER(rc.report_component_code) LIKE '%{$searchTerm}%' OR LOWER(rc.short_desc) LIKE '%{$searchTerm}%' OR LOWER(rt.report_type_desc) LIKE '%{$searchTerm}%')");
		$this->db->where("rc.del_flag",0);
//        $this->db->where("trperizinan_id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})");
        $this->db->join("report_types rt", "rc.report_type=rt.report_type_code", "LEFT");
        $this->db->from('report_components rc');
		$count=$this->db->count_all_results();

        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        
        if($total_pages!=0){

			$this->db->select('rc.id, rc.report_component_code, rc.short_desc, rt.report_type_desc');
			$this->db->where("(LOWER(rc.report_component_code) LIKE '%{$searchTerm}%' OR LOWER(rc.short_desc) LIKE '%{$searchTerm}%' OR LOWER(rt.report_type_desc) LIKE '%{$searchTerm}%')");
			$this->db->where("rc.del_flag",0);
//            $this->db->where("trperizinan_id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})");
            $this->db->join("report_types rt", "rc.report_type=rt.report_type_code", "LEFT");
            $this->db->order_by($sidx,$sord);
			$query=$this->db->get('report_components rc',$limit,$start);
			
        }
        $response=new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;    
        $i=0;
        
        if(count($query->result())>0){
            foreach ($query->result() as $result)
			{
                $response->rows[$i]['id']=$result->id;
                $response->rows[$i]['report_component_code']=$result->report_component_code;
                $response->rows[$i]['short_desc']=$result->short_desc;
                $response->rows[$i]['report_type_desc']=$result->report_type_desc;
                $i++;
            }
        }else{
            $response->rows[$i]['id']="";
            $response->rows[$i]['report_component_code']="";
            $response->rows[$i]['short_desc']="";
            $response->rows[$i]['report_type_desc']="";
        }
        echo json_encode($response);
        exit();
	}
	
	
	function test(){
		echo "<pre>";print_r($this->Report_component_model->get_report_component('BAP',19, 1807));exit();
	}
}
?>