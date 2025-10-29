<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of perizinan class
 * Class untuk Report Generator
 * @author  Indra Halim
 * @since   1.0
 *
 */

class Report_generator extends WRC_AdminCont {

	private $_join_type_first=array('Main'=>'Main');
	private $_join_type_options=array('Direct'=>'Direct','Inner Join'=>'Inner Join','Right Join'=>'Right Join','Left Join'=>'Left Join');
	private $_relation_types=array('And'=>'And','Or'=>'Or');
	private $_conditional_types=array('='=>'=','<>'=>'<>','>'=>'>','<'=>'<','>='=>'>=','<='=>'<=');
	private $_filter_types=array('date'=>'date','text'=>'text','multi_dropdown'=>'multiple dropdown','single_dropdown'=>'single dropdown');

private $_query_property=<<<'EOD'
	SELECT  c.n_property, f.satuan, a.v_property, a.v_tinjauan, g.n_property AS 'Parent'  FROM tmproperty_jenisperizinan a 
	INNER JOIN tmproperty_jenisperizinan_trproperty b ON a.id=b.tmproperty_jenisperizinan_id 
	INNER JOIN trproperty c ON b.trproperty_id=c.id 
	INNER JOIN tmpermohonan d ON a.pendaftaran_id=d.pendaftaran_id 
	INNER JOIN tmpermohonan_trperizinan e ON d.id= e.tmpermohonan_id 
	INNER JOIN trperizinan_trproperty f ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id)
	INNER JOIN trproperty g ON f.c_parent=g.id WHERE d.id={\$tmpermohonan_id} 
	ORDER BY f.c_parent_order ASC, f.c_order ASC
EOD;
	private $_option_report_type=array();
	private $_jasper_folder='';

    public function __construct() {
        parent::__construct();
	
//        $this->perizinan = new trperizinan();
		$this->load->model('Report_generator_model');
		$this->Report_generator_model=new Report_generator_model();
		
		$option_report_types=$this->Report_generator_model->get_report_types();
		$option_report_types=array('-1'=>'-Please Select-')+$option_report_types;
		$this->_option_report_type=$option_report_types;
		
		$this->_jasper_folder=realpath(BASEPATH."..".DIRECTORY_SEPARATOR."assets".DIRECTORY_SEPARATOR."jasper");
    }
	
	/*private function _check_auth(){
        $enabled = FALSE;//enable hak akses
        $list_auths = $this->session_info['app_list_auth'];

        foreach ($list_auths as $list_auth) {
            if($list_auth->id_role === '19') {
                $enabled = TRUE;
            }
        }

        if(!$enabled) {
            redirect('dashboard');
        }
	}*/
	
	private function _load_additional_model(){
		$this->load->model('Report_group_data_model');
		$this->Report_group_data_model=new Report_group_data_model();		
		
		$this->load->model('Report_table_model');
		$this->Report_table_model=new Report_table_model();
		
		$this->load->model('Report_field_model');
		$this->Report_field_model=new Report_field_model();
		
		$this->load->model('Report_condition_model');
		$this->Report_condition_model=new Report_condition_model();

		$this->load->model('Report_filter_model');
		$this->Report_filter_model = new Report_filter_model();

        $this->load->model('Report_subreport_model');
		$this->Report_subreport_model=new Report_subreport_model();
	}

    public function index() {
//		$this->_check_auth();
		
//        $data['list'] = $this->perizinan->get();
//        $data['list_izin'] = $this->perizinan->get_list();
		if(!empty($_POST)){
			//Jika form sudah dipost, tentukan apakah action tersebut merupakan create atau open
			$clicked_button=$this->input->post('clicked_button');
			$report_code=$this->input->post('report_code');
			
			if($clicked_button=='btn_create'){
				redirect('report_generator/add/'.$report_code);
			}elseif($clicked_button=='btn_open'){
				$report_id=$this->input->post('report_id');
				redirect('report_generator/edit/'.$report_id);
			}elseif($clicked_button=='btn_copy'){
				$report_id=$this->input->post('report_id');
				redirect('report_generator/copy/'.$report_id);
			}else{
				redirect('report_generator');
			}
		}
        $this->session_info['page_name'] = "Report Generator";
        $this->template->build('index', $this->session_info);
    }
	
	public function add($report_code=NULL){
//		$this->_check_auth();

		$data = $this->_prepareForm();

		if(!is_null($report_code)){
			$data['report_code']=$report_code;
		}else{
			$data['report_code']='';
		}
		/*$data['group_types']=json_encode(array('F'=>'Form','T'=>'Table Data','P'=>'Property Form'));
		$data['direct_query_list']=json_encode(array(0=>'No',1=>'Yes'));
		$data['option_perizinan']=array('-1'=>'-Please Select-')+$this->Report_generator_model->get_perizinan_list();
		$data['option_report_type']=$this->_option_report_type;
        $data['display_type'] = $this->Report_generator_model->displayType;*/

		//$data['option_report_component']=array('-1'=>'-Please Select-')+$this->Report_generator_model->get_report_component();
		$this->load->vars($data);
		
		$js="";
        $this->template->set_metadata_javascript($js);		
		$this->session_info['page_name'] = "Add Report";
		$this->template->build('add', $this->session_info);
	}
	
	public function edit($report_id=null){
//		$this->_check_auth();

        $data = $this->_prepareForm();
		$this->_load_additional_model();
	
		$data['report_data']=$this->Report_generator_model->get_data($report_id);
		$data['report_group_datas']=$this->Report_group_data_model->get_all($report_id);
		$data['report_subreports']=$this->Report_subreport_model->get_all($report_id);

		/*$data['group_types']=json_encode(array('F'=>'Form','T'=>'Table Data','P'=>'Property Form'));
		$data['direct_query_list']=json_encode(array('0'=>'No','1'=>'Yes'));
		$data['option_perizinan']=array('-1'=>'-Please Select-')+$this->Report_generator_model->get_perizinan_list();
		$data['option_report_type']=$this->_option_report_type;
        $data['display_type'] = $this->Report_generator_model->displayType;*/

		//$data['option_report_component']=array('-1'=>'-Please Select-')+$this->Report_generator_model->get_report_component();
		
		$this->load->vars($data);
		$js="";
        $this->template->set_metadata_javascript($js);		
		$this->session_info['page_name'] = "Edit Report";
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

        $data['group_types']=json_encode(array('F'=>'Form','T'=>'Table Data','P'=>'Property Form'));
        $data['direct_query_list']=json_encode(array(0=>'No',1=>'Yes'));
        $data['option_perizinan']=array('-1'=>'-Please Select-')+$this->Report_generator_model->get_perizinan_list();
        $data['option_report_type']=$this->_option_report_type;
        $data['display_type'] = $this->Report_generator_model->displayType;
        $data['opsiUnitKerja'] = $opsiUnitKerja;
        return $data;
    }

	public function copy($report_id=null){
//		$this->_check_auth();
		
		$this->_load_additional_model();
		$old_group_data_ids=array();
	
		//baca table report_generator
		$data['report_data']=$this->Report_generator_model->get_data($report_id,'array');
		unset($data['report_data']['id']);//hapus idnya agar saat menyimpan ke tabel report_generator akan diinsert sebagai record baru
		$data['report_data']['report_code'].='-Copy';
		
		//baca tabel report subreport
		$data['report_subreports']=$this->Report_subreport_model->get_all($report_id,'array');
		
		//baca tabel report group data
		$data['report_group_datas']=$this->Report_group_data_model->get_all($report_id,'array');

		$response=$this->Report_generator_model->save($data['report_data']);
		if($response['success']==true){//Jika berhasil menyimpan ke table report_generators
			$report_generator_id=$response['id'];
			
			foreach($data['report_group_datas']['rows'] as $key=>$value){
				$old_group_data_ids[]=$value['id'];//Tampung semua id group data yang ada untuk dibaca detailnya
			}
			
			//Jika ada Group Data, simpan juga
			if(!empty($data['report_group_datas']['rows'])){
				//save ke tabel report group data
				$response_group_data=$this->Report_group_data_model->save_all($data['report_group_datas']['rows'],$report_generator_id,"insert");
				$response['group_data']=$response_group_data;
				
				//save ke tabel report subreport
				$response_subreport=$this->Report_subreport_model->save_all($data['report_subreports']['rows'],$report_generator_id,"insert");
				
				$data2=array();
				foreach($response['group_data'] as $seq=>$group){
					//ambil detail dari masing-masing group dan insert lagi dengan id baru 
					$new_report_group_data_id=$group['id'];
					$report_group_data_id=$old_group_data_ids[$seq];
					
					//[START] Membaca data dari tabel report_table, report_field, report_condition, dan report filter
					$data2['report_tables']=$this->Report_table_model->get_all($report_group_data_id,'array');
					$data2['report_fields']=$this->Report_field_model->get_all($report_group_data_id,'array');					
					$data2['report_conditions']=$this->Report_condition_model->get_all($report_group_data_id,'array',false);
                    $data2['report_filters']=$this->Report_filter_model->get_all($report_group_data_id,'array');
                    //[END]

					//[START] SIMPAN KE TABLE report_table, report_field, dan report_condition
					$response_table=$this->Report_table_model->save_all($data2['report_tables']['rows'],$report_generator_id,$new_report_group_data_id,"insert");
					$response_field=$this->Report_field_model->save_all($data2['report_fields']['rows'],$report_generator_id,$new_report_group_data_id,"insert");
					$response_condition=$this->Report_condition_model->save_all($data2['report_conditions']['rows'],$report_generator_id,$new_report_group_data_id,"insert");
                    $response_filter=$this->Report_filter_model->save_all($data2['report_filters']['rows'],$report_generator_id,$new_report_group_data_id,"insert");
					//[END]
				}
			}
		}

		redirect('report_generator/edit/'.$report_generator_id);
	}
	
	public function delete(){
		if(!empty($_POST['report_generator_id'])){
			$report_generator_id = $this->input->post('report_generator_id');
			echo $this->Report_generator_model->delete($report_generator_id);
		}else{
			echo false;
		}
	}
	
	private function _remove_whitespace($string){
		return preg_replace("/\s+/", " ",$string );
	}
	
	public function save_mainform(){
		if(!empty($_POST)){
			$this->_load_additional_model();
			$response=array();
			$response_group_data=array();
			$response_subreport_data=array();
			$data=$_POST['data'];
			
			//echo "<pre>";print_r($data);exit()
			
			//Cek apakah sudah ada settting untuk report ini atau belum
			if(!isset($data['ReportGenerator']['id'])){
				$data['ReportGenerator']['id']='';
			}
            switch($data['ReportGenerator']['display_type']){
                case 'standalone':
			        $check_duplicate = $this->Report_generator_model->no_same_id_exists($data['ReportGenerator']['display_type'], $data['ReportGenerator']['id']);
                    break;
                default:
			        $check_duplicate = $this->Report_generator_model->no_report_setting_exists($data['ReportGenerator']['report_type'],$data['ReportGenerator']['trperizinan_id'],$data['ReportGenerator']['id'], $data['ReportGenerator']['trunitkerja_id']);
                    break;
            }

			if($check_duplicate['status']==true){
				$response=$this->Report_generator_model->save($data['ReportGenerator']);
				if($response['success']==true){//Jika sukses save ke table report_generators
					//save ke tabel report_group_datas
					$report_generator_id=$response['id'];
					
					//Jika Group Data juga disimpan
					if(!empty($data['ReportGroupData'])){
						$response_group_data=$this->Report_group_data_model->save_all($data['ReportGroupData'],$report_generator_id);
						$response['group_data']=$response_group_data;
					}
					
					if(!empty($data['ReportSubreport'])){
						$this->Report_subreport_model->delete_all($report_generator_id);
						$response_subreport_data=$this->Report_subreport_model->save_all($data['ReportSubreport'],$report_generator_id);
						$response['subreport_data']=$response_subreport_data;
					}
				}
			}else{
				$response['success']=false;
				$response['message']=$check_duplicate['message'];
			}
			echo json_encode($response);exit();
		}
	}
	
	public function save_detailform(){
		if(!empty($_POST)){
			$this->_load_additional_model();
			$success=false;
			$response=array();
			$response_group_data=array();
			$data=$_POST['data'];
			
			$report_generator_id=$data['ReportGroupData']['report_generator_id'];
			$report_group_data_id=$data['ReportGroupData']['id'];
			
			$response_table=$this->Report_table_model->save_all($data['ReportTable'],$report_generator_id,$report_group_data_id);
			if(isset($data['ReportField'])&&!empty($data['ReportField'])){
				$response_field=$this->Report_field_model->save_all($data['ReportField'],$report_generator_id,$report_group_data_id);
			}else{
				$response_field['overall_success']=true;
			}
//			echo "<pre>";print_r($data);exit();
			if(isset($data['ReportCondition'])&&!empty($data['ReportCondition'])){
				$response_condition=$this->Report_condition_model->save_all($data['ReportCondition'],$report_generator_id,$report_group_data_id);
			}else{
				$response_condition['overall_success']=true;
			}
			if($response_table['overall_success']&&
				$response_field['overall_success']&&
				$response_condition['overall_success']){
				$success=true;	
			}else{
				$success=false;
			}
			$response['success']=$success;
			$response['report_table']=$response_table;
			$response['report_field']=$response_field;
			$response['report_condition']=$response_condition;
			
			$query=$this->generate_query($report_generator_id,$report_group_data_id);
			$xdata['id']=$report_group_data_id;
			$xdata['group_query']=$query;
			$this->Report_group_data_model->save($xdata);
			$response['group_query']=$query;
			echo json_encode($response);
			
			exit();
			
		}
	}

    public function save_filterform(){
        if(!empty($_POST)){
            $this->load->model('Report_group_data_model');
            $this->Report_group_data_model=new Report_group_data_model();

            $this->load->model('Report_filter_model');
            $this->Report_filter_model =new Report_filter_model();

            $success=false;
            $response=array();
            $response_group_data=array();
            $data=$_POST['data'];

            $report_generator_id=$data['ReportGroupData']['report_generator_id'];
            $report_group_data_id=$data['ReportGroupData']['id'];

			if(isset($data['ReportFilter'])&&!empty($data['ReportFilter'])){
                $response_filter=$this->Report_filter_model->save_all($data['ReportFilter'],$report_generator_id,$report_group_data_id);
            }else{
                $response_filter['overall_success']=true;
            }
            if($response_filter['overall_success']){
                $success=true;
            }else{
                $success=false;
            }
            $response['success']=$success;
            $response['report_filter']=$response_filter;
            echo json_encode($response);
            exit();
        }
    }
	
	public function save_joinform(){
		if(!empty($_POST)){
			$this->_load_additional_model();
			$success=false;
			$response=array();
			$response_group_data=array();
			$data=$_POST['data'];
			
			$report_generator_id=$data['ReportTable']['report_generator_id'];
			$report_group_data_id=$data['ReportTable']['report_group_data_id'];
			
//			echo "<pre>";print_r($data);exit();
			$response_condition=$this->Report_condition_model->save_all($data['ReportCondition'],$report_generator_id,$report_group_data_id);
			if($response_condition['overall_success']){
				$success=true;	
			}else{
				$success=false;
			}
			$response['success']=$success;
			$response['report_condition']=$response_condition;
			echo json_encode($response);exit();
		}
	}
	
	public function delete_group(){
		$result=false;
		if(!empty($_POST)){
			$this->load->model('Report_group_data_model');
			$this->Report_group_data_model=new Report_group_data_model();
			$id=$_POST['id'];
			$result=$this->Report_group_data_model->delete($id);
		}
		echo $result;exit();
	}
	
	public function detail($report_group_data_id=NULL){
		if($report_group_data_id){
			$js="";
			$this->_load_additional_model();
			
			$this->template->set_metadata_javascript($js);
			$data['join_type_first']=json_encode($this->_join_type_first);
			$data['join_type_options']=json_encode($this->_join_type_options);
			$data['relation_type']=json_encode($this->_relation_types);
			$data['conditional_type']=json_encode($this->_conditional_types);
			
			$data['report_group_data']=$this->Report_group_data_model->get_data($report_group_data_id);
			$data['report_tables']=$this->Report_table_model->get_all($report_group_data_id);
			$data['report_fields']=$this->Report_field_model->get_all($report_group_data_id);
			$data['report_conditions']=$this->Report_condition_model->get_all($report_group_data_id);
			$data['direct_code']=$this->_join_type_options['Direct'];
			$this->load->vars($data);
			$this->load->view('detail');
		}
	}

    public function detail_filter($report_group_data_id=NULL){
        if($report_group_data_id){
            $js="";
            $this->load->model('Report_group_data_model');
            $this->Report_group_data_model=new Report_group_data_model();

            $this->load->model('Report_filter_model');
            $this->Report_filter_model =new Report_filter_model();

            $this->template->set_metadata_javascript($js);
            $data['filter_type']=json_encode($this->_filter_types);

            $data['report_group_data']=$this->Report_group_data_model->get_data($report_group_data_id);
            $data['report_filters']=$this->Report_filter_model->get_all($report_group_data_id);
            $this->load->vars($data);
            $this->load->view('detail_filter');
        }
    }
	
	public function join_condition($report_table_id=null){
		if($report_table_id){
			$js="";
			$this->_load_additional_model();
			$report_table=$this->Report_table_model->get_data($report_table_id);
			$data['report_table']=$report_table;
			$data['report_conditions']=$this->Report_condition_model->get_join_conditions($report_table->report_generator_id,$report_table->report_group_data_id,$report_table->table_name);

			$this->template->set_metadata_javascript($js);
			$data['relation_type']=json_encode($this->_relation_types);
			$data['conditional_type']=json_encode($this->_conditional_types);
			$this->load->vars($data);
			$this->load->view('joincon');
		}
	}
	
	public function combo_grid_report(){
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
        if(!$sidx) $sidx ='rg.report_code';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        }

        $this->db->where("(LOWER(rg.report_code) LIKE '%{$searchTerm}%' OR LOWER(rg.short_desc) LIKE '%{$searchTerm}%' OR LOWER(rt.report_type_desc) LIKE '%{$searchTerm}%')");
        $this->db->where("rg.del_flag",0);
        $this->db->join("report_types rt", "rg.report_type=rt.report_type_code", "LEFT");
//        $this->db->where("trperizinan_id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})");
        $this->db->from('report_generators rg');
        $count=$this->db->count_all_results();

        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)

        if($total_pages!=0){
            $this->db->select('rg.id, rg.report_code, rg.short_desc, rt.report_type_desc');
            $this->db->where("(LOWER(rg.report_code) LIKE '%{$searchTerm}%' OR LOWER(rg.short_desc) LIKE '%{$searchTerm}%' OR LOWER(rt.report_type_desc) LIKE '%{$searchTerm}%')");
            $this->db->where("rg.del_flag",0);
            $this->db->join("report_types rt", "rg.report_type=rt.report_type_code", "LEFT");
//            $this->db->where("trperizinan_id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})");
            $this->db->order_by($sidx,$sord);
            $query=$this->db->get('report_generators rg',$limit,$start);

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
                $response->rows[$i]['report_code']=$result->report_code;
                $response->rows[$i]['short_desc']=$result->short_desc;
                $response->rows[$i]['report_type_desc']=$result->report_type_desc;
                $i++;
            }
        }else{
            $response->rows[$i]['id']="";
            $response->rows[$i]['report_code']="";
            $response->rows[$i]['short_desc']="";
            $response->rows[$i]['report_type_desc']="";
        }
        echo json_encode($response);
        exit();
	}

    public function combo_grid_report_display(){
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

        $this->db->where("(LOWER(report_code) LIKE '%{$searchTerm}%' OR LOWER(short_desc) LIKE '%{$searchTerm}%')");
        $this->db->where("del_flag",0);
        $this->db->where("display_type","standalone");
        $this->db->from('report_generators');
        $count=$this->db->count_all_results();

        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)

        if($total_pages!=0){

            $this->db->select('id,report_code,short_desc');
            $this->db->where("(LOWER(report_code) LIKE '%{$searchTerm}%' OR LOWER(short_desc) LIKE '%{$searchTerm}%')");
            $this->db->where("del_flag",0);
            $this->db->where("display_type","standalone");
            $this->db->order_by($sidx,$sord);
            $query=$this->db->get('report_generators',$limit,$start);

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
                $response->rows[$i]['report_code']=$result->report_code;
                $response->rows[$i]['short_desc']=$result->short_desc;
                $i++;
            }
        }else{
            $response->rows[$i]['id']="";
            $response->rows[$i]['report_code']="";
            $response->rows[$i]['short_desc']="";
        }
        echo json_encode($response);
        exit();
    }
	
	public function combo_grid_tablename(){
		$this->db = $this->load->database('default', TRUE);
		$database=$this->db->database;
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
        if(!$sidx) $sidx ='table_name';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        } 

		$run_query=$this->db->query("SELECT COUNT(table_name)as total FROM information_schema.tables WHERE TABLE_SChEMA= '$database' AND table_name LIKE '%{$searchTerm}%' ORDER BY table_name ASC");
		
		$result=$run_query->result();
    	$total=$result[0]->total;
		$count=$total; 
		 
        if( $count >0 ) {
                $total_pages = ceil($count/$limit);
        } else {
                $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        
        if($total_pages!=0){
			$query=$this->db->query("SELECT table_name FROM information_schema.tables WHERE TABLE_SCHEMA= '$database' AND table_name LIKE '%{$searchTerm}%' ORDER BY $sidx $sord LIMIT $start, $limit");
        }
        $response=new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;    
        $i=0;
        
        if(count($query->result())>0){
            foreach ($query->result() as $result)
			{
                $response->rows[$i]['table_name']=$result->table_name;
                $i++;
            }
        }else{
                $response->rows[$i]['table_name']="";	
        }
        echo json_encode($response);
        exit();
	}

    /**
     * Combo Grid Registered Table
     */
	public function cg_reg_table(){
		$this->load->library('MY_Input');

		$total_pages=0;
        $page=$this->input->get('page');
        $limit =$this->input->get('rows'); // get how many rows we want to have into the grid
        $sidx =$this->input->get('sidx'); // get index row - i.e. user click to sort
        $sord =$this->input->get('sord'); // get the direction
        $other_param =$this->input->get('otherParam'); //mengambil parameter lain, menggunakan combo_grid custom
		$report_group_id=$other_param['report_group_id'];

        if($this->input->get('searchTerm')){
			$searchTerm = $this->input->get('searchTerm');        
        }else{
            $searchTerm = "";
        }
        if(!$sidx) $sidx ='table_name';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        } 
	
		$this->db->where("LOWER(table_name) LIKE '%{$searchTerm}%'");
		$this->db->where("report_group_data_id",$report_group_id);
		$this->db->from('report_tables');
		$count=$this->db->count_all_results();
		 
        if( $count >0 ) {
                $total_pages = ceil($count/$limit);
        } else {
                $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        
        if($total_pages!=0){
			$this->db->select('table_name');
			$this->db->where("LOWER(table_name) LIKE '%{$searchTerm}%'");
			$this->db->where("report_group_data_id",$report_group_id);
			$this->db->order_by($sidx,$sord);
			$query=$this->db->get('report_tables',$limit,$start);
        }
        $response=new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;    
        $i=0;
        
        if(count($query->result())>0){
            foreach ($query->result() as $result)
			{
                $response->rows[$i]['table_name']=$result->table_name;
                $i++;
            }
        }else{
                $response->rows[$i]['table_name']="";	
        }
        echo json_encode($response);
        exit();
	}

    /**
     * Combo Grid Registered Field
     */
	public function cg_reg_field(){
		$this->db = $this->load->database('default', TRUE);
		$database=$this->db->database;
		$this->load->library('MY_Input');

		$total_pages=0;
        $page=$this->input->get('page');
        $limit =$this->input->get('rows'); // get how many rows we want to have into the grid
        $sidx =$this->input->get('sidx'); // get index row - i.e. user click to sort
        $sord =$this->input->get('sord'); // get the direction
        $other_param =$this->input->get('otherParam');// mengambil parameter lain, menggunakan combo_grid custom
		$table_name=$other_param['tbl_name'];
		
        if($this->input->get('searchTerm')){
			$searchTerm = $this->input->get('searchTerm');        
        }else{
            $searchTerm = "";
        }
        if(!$sidx) $sidx ='column_name';
        if ($searchTerm=="") {
            $searchTerm="%";
        } else {
            $searchTerm = "%" . $searchTerm . "%";
        } 
	
		$run_query=$this->db->query("SELECT COUNT(column_name)as total FROM INFORMATION_SCHEMA.COLUMNS WHERE  table_schema = '$database' AND table_name = '$table_name' AND column_name LIKE '%{$searchTerm}%'");
		
		$result=$run_query->result();
    	$total=$result[0]->total;
		$count=$total; 
		 
        if( $count >0 ) {
                $total_pages = ceil($count/$limit);
        } else {
                $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        
        if($total_pages!=0){
			$query=$this->db->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_schema = '$database' AND table_name = '$table_name' AND column_name LIKE '%{$searchTerm}%' ORDER BY $sidx $sord LIMIT $start, $limit");
        }
        $response=new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;    
        $i=0;
        
        if(count($query->result())>0){
            foreach ($query->result() as $result)
			{
                $response->rows[$i]['column_name']=$result->column_name;
                $i++;
            }
        }else{
                $response->rows[$i]['column_name']="";	
        }
        echo json_encode($response);
        exit();
	}
	
	public function delete_table(){
		$result=false;
		if(!empty($_POST)){
			$id=$_POST['id'];
			$this->load->model('Report_table_model');
			$this->Report_table_model=new Report_table_model();
			$result=$this->Report_table_model->delete($id);
		}
		echo $result;exit();		
	}
	
	public function delete_field(){
		$result=false;
		if(!empty($_POST)){
			$id=$_POST['id'];
			$this->load->model('Report_field_model');
			$this->Report_field_model=new Report_field_model();
			$result=$this->Report_field_model->delete($id);
		}
		echo $result;exit();		
	}
	
	public function delete_condition(){
		$result=false;
		if(!empty($_POST)){
			$id=$_POST['id'];
			$this->load->model('Report_condition_model');
			$this->Report_condition_model=new Report_condition_model();
			$result=$this->Report_condition_model->delete($id);
		}
		echo $result;exit();		
	}

    public function delete_filter(){
        $result=false;
        if(!empty($_POST)){
            $id=$_POST['id'];
            $this->load->model('Report_filter_model');
            $this->Report_filter_model = new Report_filter_model();
            $result=$this->Report_filter_model->delete($id);
        }
        echo $result;exit();
    }
	
	function generate_query($report_id=null,$report_group_id=null){
		$data=array();
		$result=false;
		$query = '';
		
		$queryJoin ="";
		if($report_id&&$report_group_id){
			$this->_load_additional_model();
			$data['report_generator']=$this->Report_generator_model->get_data($report_id);
			$data['report_group']=$this->Report_group_data_model->get_data($report_group_id);
			
			foreach($data['report_generator'] as $property => $value)  { 
			     $report['report_generator'][$property] = $value;
			}
			foreach($data['report_group'] as $property => $value)  { 
			    $report['report_group'][$property] = $value;
			}
			
			if($report['report_group']['use_direct_query']=="1"){
				$query = $report['report_group']['direct_query'];
			}else{
				$report['report_table'] = $this->Report_table_model->get_all_report_table($report_group_id,$report_id);
				$report['report_field'] = $this->Report_field_model->get_all_report_field($report_group_id,$report_id);
				$report['report_condition'] = $this->Report_condition_model->get_all_report_condition($report_group_id,$report_id);
				$report['report_jointable']= $this->Report_table_model->get_all_report_jointable($report_group_id,$report_id);
				/*====================================*/
				$querySelect = 'SELECT ';

                if(!empty($report['report_field']['rows'])){
                    for($x=0;$x<$report['report_field']['num_rows'];$x++){
                        if($x!=0){
                            $querySelect.=', ';
                        }
                        $querySelect.= $report['report_field']['rows'][$x]->table_name.'.';
                        $querySelect.= $report['report_field']['rows'][$x]->field;
                        if($report['report_field']['rows'][$x]->field_alias !=''){
                            $querySelect.= ' AS '. '\''.$report['report_field']['rows'][$x]->field_alias.'\'';
                        }
                    }
                }else{
                    $querySelect.= '* ';
                }
				
				$queryJoin ="";
				//echo "<pre>";
				if($report['report_jointable']['num_rows']>0){
					for($x=0;$x<$report['report_jointable']['num_rows'];$x++){
						$joincondition = $this->Report_condition_model->get_join_conditions($report_id,$report_group_id,$report['report_jointable']['rows'][$x]->table_name);
						//print_r($joincondition);
						$queryJoin.=$report['report_jointable']['rows'][$x]->join_type.' ';
						$queryJoin.=$report['report_jointable']['rows'][$x]->table_name.' ON (';
					
						for($y=0;$y<$joincondition['num_rows'];$y++){
						
							if($joincondition['rows'][$y]->report_table_field2==''||$joincondition['rows'][$y]->report_table_field2==NULL){
								$queryJoin.=$joincondition['rows'][$y]->report_table_field1.'.'.$joincondition['rows'][$y]->report_field1;
								$queryJoin.=$joincondition['rows'][$y]->condition_type;
								$queryJoin.=$joincondition['rows'][$y]->report_field2;
							}else{
								$queryJoin.=$joincondition['rows'][$y]->report_table_field1.'.'.$joincondition['rows'][$y]->report_field1;
								$queryJoin.=$joincondition['rows'][$y]->condition_type;
								$queryJoin.=$joincondition['rows'][$y]->report_table_field2.'.'.$joincondition['rows'][$y]->report_field2;
							}
							if($y+1!=$joincondition['num_rows']){
								$queryJoin.=' '.$joincondition['rows'][$y]->relation_type.' ';
							}

						}						
						$queryJoin.=' )';
						
					}
				}
	
				$queryFrom = "FROM ";
				for($x=0;$x<$report['report_table']['num_rows'];$x++){
					if($report['report_table']['rows'][$x]->join_type=='Direct'||$report['report_table']['rows'][$x]->join_type=='Main'){
						if($x!=0){
							$queryFrom.=', ';
						}
						$queryFrom.= $report['report_table']['rows'][$x]->table_name;
					}		
				}
				
				$queryCon = "";
                if(!empty($report['report_condition']['num_rows'])){
				    $queryCon = "WHERE ";
                    for($x=0;$x<$report['report_condition']['num_rows'];$x++){
                        if($report['report_condition']['rows'][$x]->report_table==''){
                            if($report['report_condition']['rows'][$x]->report_table_field2==''||$report['report_condition']['rows'][$x]->report_table_field2==NULL){
                                $queryCon.=$report['report_condition']['rows'][$x]->report_table_field1.'.'.$report['report_condition']['rows'][$x]->report_field1;
                                $queryCon.=$report['report_condition']['rows'][$x]->condition_type;
                                $queryCon.=$report['report_condition']['rows'][$x]->report_field2;
                            }else{
                                $queryCon.=$report['report_condition']['rows'][$x]->report_table_field1.'.'.$report['report_condition']['rows'][$x]->report_field1;
                                $queryCon.=$report['report_condition']['rows'][$x]->condition_type;
                                $queryCon.=$report['report_condition']['rows'][$x]->report_table_field2.'.'.$report['report_condition']['rows'][$x]->report_field2;
                            }
                            if($x+1!=$report['report_condition']['num_rows']){
                                $queryCon.=' '.$report['report_condition']['rows'][$x]->relation_type.' ';
                            }
                        }
                    }
                }
			}
			
			$query = $querySelect.' '.$queryFrom.' '.$queryJoin.' '.$queryCon;
			return $query;
		}
	}
	
	function generate_xml($report_type,$tmpermohonan_id,$trperizinan_id, $tim_teknis_id = null){
	
		$trperizinan_id=$trperizinan_id;
		$tmpermohonan_id=$tmpermohonan_id;
		$singular_data=array();
		$no_table_data=true;
		//mengambil query-query untuk report yang diinginkan
		$group_datas=$this->Report_generator_model->get_group_query($report_type,$trperizinan_id, $tmpermohonan_id);
		$this->load->library('Terbilang');
		if(!empty($group_datas)){
			
			$dom = new DomDocument('1.0');
			$root = $dom->appendChild($dom->createElement("result"));
			foreach($group_datas as $group_data){
				$group_data_code=$group_data['report_group_code'];
				$type=$group_data['type'];
				$direct_query=$group_data['direct_query'];
				$group_query=$group_data['group_query'];
				$use_direct_query=$group_data['use_direct_query'];
				
				if($use_direct_query==1){//Jika  tipenya tabel atau multiple record
					$str_query=$direct_query;
				}else{
					$str_query=$group_query;
				}
				
				eval("\$str_query = \"$str_query\";");

				$query=$this->db->query($str_query);
				$records=$query->result_array();
				
				if(!empty($records)){
										
					if($type=='T'){//Jika tipenya tabel/multiple record
						
						$induk = $root->appendChild($dom->createElement($group_data_code));
						foreach($records as $record){
							$anak1 = $induk->appendChild($dom->createElement("data"));
							foreach($record as $field=>$value){
								$value=stripslashes($value);
								$anak2 = $anak1->appendChild($dom->createElement($field));
								
								##Konversi ke tanggal Bahasa Indonesia##
								if($this->_isValidDate($value)){
									##Menambahkan nama hari
									$anak3 = $anak1->appendChild($dom->createElement($field.'_day'));						
									$day=$this->lib_date_repgen->get_day($value);
									$anak3->appendChild($dom->createTextNode($day));
									####
									
									##Menambahkan nama hari
									$anak4 = $anak1->appendChild($dom->createElement($field.'_hijriah'));
                                    $tgl_hijriah=$this->lib_date_repgen->mysql_to_hijriah($value);
									$anak4->appendChild($dom->createTextNode($tgl_hijriah));
									####

									$value=$this->lib_date_repgen->mysql_to_human($value);
								}
								##############
								
								$anak2->appendChild($dom->createTextNode($value));
							}
						}
						$no_table_data=false;
						
					}elseif($type=='P'){//Jika tipe datanya Property Form
						foreach($records as $index=>$value){
							$fields=array_keys($value);
							if(count($fields)>1){//Ambil value 1 sebagai nama param, dan value 2 sebagai nilainya
								$property_param_name=strtolower(str_replace(' ','',$group_data_code.'_'.$value[$fields[0]]));
								$property_value=$value[$fields[1]];
								$singular_data[$group_data_code][$property_param_name]=$property_value;
								
								if($this->_isValidDate($property_value)){
									$day=$this->lib_date_repgen->get_day($property_value);
									$tgl_hijriah=$this->lib_date_repgen->mysql_to_hijriah($property_value);
									$value=$this->lib_date_repgen->mysql_to_human($property_value);
									
									$singular_data[$group_data_code][$property_param_name.'_hijriah'] = $tgl_hijriah;
									$singular_data[$group_data_code][$property_param_name.'_day'] = $day;
								}
								if(is_numeric($property_value)){//Jika value adalah numeric, maka sediakan terbilang dari value tersebut
									$terbilang=$this->terbilang->terbilang($property_value);
									$singular_data[$group_data_code][$property_param_name.'_terbilang'] = $terbilang;
								}
								
							}else{//Jika tidak ada 2 field, maka keluar dari looping
								break;
							}
						}
					}else{//Jika tipe tabelnya Form
											
						##untuk konversi ke tanggal bahasa indonesia##		
						foreach($records[0] as $field=>$value){
							$value=stripslashes($value);
							if($this->_isValidDate($value)){
								$day=$this->lib_date_repgen->get_day($value);
								$tgl_hijriah=$this->lib_date_repgen->mysql_to_hijriah($value);
								$value=$this->lib_date_repgen->mysql_to_human($value);
								
								$records[0][$field.'_hijriah'] = $tgl_hijriah;
								$records[0][$field.'_day'] = $day;
							}
							if(is_numeric($value)){//Jika value adalah numeric, maka sediakan terbilang dari value tersebut
								$terbilang=$this->terbilang->terbilang($value);
								$records[0][$field.'_terbilang'] = $terbilang;
							}
							$records[0][$field] = $value;
						}
						$records[0]['repgen_tgl_sekarang']=$this->lib_date_repgen->get_tgl_sekarang();
                        $records[0]['repgen_day']=$this->lib_date_repgen->get_day(date('Y-m-d'));
                        $records[0]['repgen_date']=date('d');
                        $records[0]['repgen_month']=date('m');
                        $records[0]['repgen_year']=date('Y');

                        ##########
						
						$singular_data[$group_data_code]=$records[0];
//						
					}
				}
			}
			$xml_path=$this->_jasper_folder.DIRECTORY_SEPARATOR."datasource".DIRECTORY_SEPARATOR.$report_type.str_replace(" ","_",microtime()).".xml";
			
			if($no_table_data){//Jika tidak ada table data sama sekali, create dummy agar jasper tidak error
				$induk = $root->appendChild($dom->createElement("dummy"));
				$anak1 = $induk->appendChild($dom->createElement("data"));
			}
			
			$dom->save($xml_path);
			$return=array(
				'xml_path'=>$xml_path,
				'singular'=>$singular_data
			);
			
			//echo 'Data Source telah berhasil dibuat.';
			
			return $return;
			exit();
		}else{
			echo 'Belum ada konfigurasi Report Generator untuk laporan ini';exit();
		}
	}
	
	function download_empty_xml($report_generator_id){
		$singular_data=array();
		$no_table_data=true;
		$tmpermohonan_id=0;
		$report_generator_data=$this->Report_generator_model->get_data($report_generator_id,'array');
		
		if(!empty($report_generator_data)){
			//mengambil query-query untuk report yang diinginkan
			$report_type=$report_generator_data['report_type'];
			$trperizinan_id=$report_generator_data['trperizinan_id'];
			$group_datas=$this->Report_generator_model->get_group_query($report_type,$trperizinan_id);
		
			if(!empty($group_datas)){
				
				$dom = new DomDocument('1.0');
				$root = $dom->appendChild($dom->createElement("result"));
				foreach($group_datas as $group_data){
					$group_data_code=$group_data['report_group_code'];
					$type=$group_data['type'];
					$direct_query=$group_data['direct_query'];
					$group_query=$group_data['group_query'];
					$use_direct_query=$group_data['use_direct_query'];
					
					if($use_direct_query==1){//Jika  tipenya tabel atau multiple record
						$str_query=$direct_query;
					}else{
						$str_query=$group_query;
					}
					
					eval("\$str_query = \"$str_query\";");
					
					$query=$this->db->query($str_query);
					$fields=$query->list_fields();
					//echo "<pre>";print_r($fields);
					//exit();
					
					if(!empty($fields)){
											
						if($type=='T'){//Jika tipenya tabel/multiple record
							
							$induk = $root->appendChild($dom->createElement($group_data_code));
							//foreach($records as $record){
								$anak1 = $induk->appendChild($dom->createElement("data"));
								foreach($fields as $index=>$field){
									$value=humanize($field);
									$anak2 = $anak1->appendChild($dom->createElement($field));
									$anak2->appendChild($dom->createTextNode($value));
								}
							//}
							$no_table_data=false;
							
						}
					}
				}
				$xml_path=$this->_jasper_folder.DIRECTORY_SEPARATOR."datasource".DIRECTORY_SEPARATOR."template_".$report_type.".xml";
				
				if($no_table_data){//Jika tidak ada table data sama sekali, create dummy agar jasper tidak error
					$induk = $root->appendChild($dom->createElement("dummy"));
					$anak1 = $induk->appendChild($dom->createElement("data"));
				}
				
				$dom->save($xml_path);
				
				/*Download file xml tersebut*/
				$this->load->helper('download');
				if(file_exists($xml_path)){
					$data = file_get_contents($xml_path); // filenya
					force_download("template_".$report_type.".xml",$data);
					@unlink($xml_path);
				}else{
					echo "Terjadi kesalahan ketika membuat template data XML. Silahkan coba lagi.";
				}
				/****************************/
				
				exit();
			}else{
				echo 'Belum ada Query Group untuk Report ini.';exit();
			}
		}else{
			echo 'Konfigurasi untuk Report ini tidak ditemukan. Silahkan simpan terlebih dahulu';
		}
	}
	
	function cetak($report_type='',$tmpermohonan_id,$trperizinan_id=NULL, $tim_teknis_id = null){
		$this->generate_report($report_type,$tmpermohonan_id,$trperizinan_id, $tim_teknis_id);
	}	
	
	function generate_report($type='BAP',$tmpermohonan_id,$trperizinan_id, $tim_teknis_id = null){
		$this->config->load('java_bridge',TRUE);
		
		/***Opsi 1: Dengan allow_url_include on***/
		//$java_bridge_lib = $this->config->item('java_bridge_lib', 'java_bridge');//Load Config java_bridge.php
		//(@include_once($java_bridge_lib))or die('Oopps koneksi ke Jasper Report gagal...Silahkan cek koneksi ke Java Bridge dan Jasper Report di server anda.');		
		/*****************************************/
		
		/***Opsi 2: Tanpa allow_url_include***/
		$java_host = $this->config->item('java_host', 'java_bridge');//Load Config java_bridge.php
		define ("JAVA_HOSTS", $java_host);
		
		$temp_javabridge=$this->_jasper_folder.DIRECTORY_SEPARATOR."Java.inc";
		
		(@include_once($temp_javabridge))or die('Oopps koneksi ke Jasper Report gagal...Silahkan cek koneksi ke Java Bridge dan Jasper Report di server anda.');		
		/************************************/
		
		$report_setting=$this->Report_generator_model->get_data_report($type,$trperizinan_id, $tmpermohonan_id);
					
		//Ambil setting untuk report component
		if(isset($report_setting['report_type'])&&$report_setting['report_type']!=''
			&&isset($report_setting['trperizinan_id'])&&$report_setting['trperizinan_id']!=''){
			$this->load->model('report_component/report_component_model');
			$this->Report_component_model=new Report_component_model();
			$report_component_data=$this->Report_component_model->get_penandatangan($report_setting['report_type'],$report_setting['trperizinan_id'], $tmpermohonan_id);
		}

		$return=$this->generate_xml($type,$tmpermohonan_id,$trperizinan_id, $tim_teknis_id);
		$report_source=str_replace(DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,$return['xml_path']);
		$singular_datas=$return['singular'];

		$pdf_name=$type.'_'.$this->_get_no_pendaftaran($tmpermohonan_id);
		$pdf_file=$this->_jasper_folder.DIRECTORY_SEPARATOR."result".DIRECTORY_SEPARATOR.$pdf_name.".pdf";

        //Hapus jika file sudah ada - Added 12 August 2014
        if(file_exists($pdf_file)){
            unlink($pdf_file);
        }

		$jrxmlfile=$this->_jasper_folder.DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR.$report_setting['layout'];
		
		
		$jcm = new Java("net.sf.jasperreports.engine.JasperCompileManager");

		$report = $jcm->compileReport($jrxmlfile);

		$jfm = new Java("net.sf.jasperreports.engine.JasperFillManager");

		$JRXml = new Java("net.sf.jasperreports.engine.data.JRXmlDataSource",$report_source,"/result//data");

		$params = new Java("java.util.HashMap");

		//echo "<pre>";print_r($singular_datas);exit();

		//mengirim parameter ke jrxml
		foreach($singular_datas as $singular){
			foreach ($singular as $param=>$value){
				$params->put($param,$value);
                //echo $param.' : '.$value."<br>";
			}
		}
		foreach($report_component_data as $param=>$value){
			$params->put('comp_'.$param,$value);
		}

		$params->put("SUBREPORT_DIR",str_replace(DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,$this->_jasper_folder.DIRECTORY_SEPARATOR."template".DIRECTORY_SEPARATOR));
		$params->put("BASEPATH",str_replace(DIRECTORY_SEPARATOR,DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR,realpath(BASEPATH."..")));
		
//		$params->put("JRXPathQueryExecuterFactory.PARAMETER_XML_DATA_DOCUMENT");
//	   	$params->put("XML_DATE_PATTERN", "yyyy-MM-dd");
//	    $params->put("XML_NUMBER_PATTERN", "#,##0.##");
//   $params->put("XML_LOCALE", "Locale.ENGLISH");

		$print = $jfm->fillReport($report, $params, $JRXml);

		$jem = new Java("net.sf.jasperreports.engine.JasperExportManager");

		$jem->exportReportToPdfFile($print, $pdf_file);

//		$jem->exportReportToHtmlFile($print, $dir . $informe . ".html");
//		if($runReport==true){

			if(file_exists($pdf_file)){
				$this->_push_file($pdf_file,$pdf_name.".pdf");
			}else{
				echo "Terjadi kesalahan ketika generate report. Silahkan coba lagi.";exit();
			}

			unlink($report_source);
			//unlink($pdf_file);
		/*} else {			

			echo "error";
		}*/
	}
	
	function _push_file($path, $name)
	{
	  // make sure it's a file before doing anything!
	  if(is_file($path)){
		    // required for IE
		    if(ini_get('zlib.output_compression')) { ini_set('zlib.output_compression', 'Off'); }

		    // get the file mime type using the file extension
		    $this->load->helper('file');

		    $mime = get_mime_by_extension($path);

		    // Build the headers to push out the file properly.
		    header('Pragma: public');     // required
		    header('Expires: 0');         // no cache
		    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		    header('Last-Modified: '.gmdate ('D, d M Y H:i:s', filemtime ($path)).' GMT');
		    header('Cache-Control: private',false);
		    header('Content-Type: '.$mime);  // Add the mime type from Code igniter.
		    header('Content-Disposition: attachment; filename="'.basename($name).'"');  // Add the file name
		    header('Content-Transfer-Encoding: binary');
		    header('Content-Length: '.filesize($path)); // provide file size
		    //header('Connection: close');
		    @readfile($path); // push it out
		    //exit();
		}
	}
	
	private function _get_no_pendaftaran($tmpermohonan_id){
		$query=$this->db->select('pendaftaran_id')->where('id',$tmpermohonan_id)->get('tmpermohonan');
		if($query){
			$result=$query->result();
			return $result[0]->pendaftaran_id;
		}
	}
	
	private function _isValidDateTime($dateTime)
	{
	    if (preg_match("/^(\d{4})-(\d{2})-(\d{2}) ([01][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/", $dateTime, $matches)) {
	        if (checkdate($matches[2], $matches[3], $matches[1])) {
	            return true;
	        }
	    }

	    return false;
	}
	
	private function _isValidDate($date)
	{
	    if (preg_match("/^(\d{4})-(\d{2})-(\d{2})$/", $date, $matches)) {
	        if (checkdate($matches[2], $matches[3], $matches[1])) {
	            return true;
	        }
	    }

	    return false;
	}
	
	public function get_property_query(){
		echo $this->_remove_whitespace($this->_query_property);
		exit();
	}
	
	public function download_layout($nama_layout=''){ //fungsi download
		if($nama_layout!=''){
			$this->load->helper('download');
			$template_folder=$this->_jasper_folder.DIRECTORY_SEPARATOR."template";
			$path_to_file=$template_folder.DIRECTORY_SEPARATOR.$nama_layout;
			if(file_exists($path_to_file)){
				$data = file_get_contents($path_to_file); // filenya
				force_download($nama_layout,$data);
			}else{
				echo "The file you're looking for doesn't exists.";exit();
			}
		}else{
			echo "The file you're looking for doesn't exists.";exit();
		}
	}
	
	public function upload_layout(){
		$error = "";
		$msg = "";
		$fileElementName = 'fileToUpload';
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{
				//$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
				//$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
				
				$file=$prefix_file = date("dmY_his")."_".$this->Report_generator_model->clean_filename($_FILES[$fileElementName]['name']);
				$directory_file=$this->_jasper_folder.DIRECTORY_SEPARATOR."template";
				$filepath = $directory_file.DIRECTORY_SEPARATOR.$file;
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'],$filepath);
				//for security reason, we force to remove all uploaded file
				@unlink($_FILES[$fileElementName]);		
				$msg .="File successfully uploaded";
		}		
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "',\n";
		echo				"file: '" . $file . "'\n";
		echo "}";
	}
	
	public function upload_subreport_layout(){
		$error = "";
		$msg = "";
		$fileElementName=$this->input->post('file_field');
		if(!empty($_FILES[$fileElementName]['error']))
		{
			switch($_FILES[$fileElementName]['error'])
			{

				case '1':
					$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
					break;
				case '2':
					$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
					break;
				case '3':
					$error = 'The uploaded file was only partially uploaded';
					break;
				case '4':
					$error = 'No file was uploaded.';
					break;

				case '6':
					$error = 'Missing a temporary folder';
					break;
				case '7':
					$error = 'Failed to write file to disk';
					break;
				case '8':
					$error = 'File upload stopped by extension';
					break;
				case '999':
				default:
					$error = 'No error code avaiable';
			}
		}elseif(empty($_FILES[$fileElementName]['tmp_name']) || $_FILES[$fileElementName]['tmp_name'] == 'none')
		{
			$error = 'No file was uploaded..';
		}else 
		{
				//$msg .= " File Name: " . $_FILES[$fileElementName]['name'] . ", ";
				//$msg .= " File Size: " . @filesize($_FILES[$fileElementName]['tmp_name']);
				
				$file=str_replace(' ','_',$_FILES[$fileElementName]['name']);
				$directory_file=$this->_jasper_folder.DIRECTORY_SEPARATOR."template";
				$filepath = $directory_file.DIRECTORY_SEPARATOR.$file;
				move_uploaded_file($_FILES[$fileElementName]['tmp_name'],$filepath);
				//for security reason, we force to remove all uploaded file
				@unlink($_FILES[$fileElementName]);		
				$msg .="File successfully uploaded";
		}		
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "',\n";
		echo				"file: '" . $file . "'\n";
		echo "}";
	}
}
?>