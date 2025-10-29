<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report generator module
* @author Indra Halim
* @version 1.0
*/
class Report_generator_model extends Model{
    private $_table = 'report_generators';

    public $displayTypeIntegrated = 'integrated';
    public $displayTypeStandalone = 'standalone';

    public $displayType = array(
        'integrated'=>'Integrasi dengan modul',
        'standalone'=>'Report standalone'
    );
	
    public function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
		
		$this->load->library('Lib_date_repgen');
		
    }
	
	public function clean_filename($filename){
        $pattern='/[^A-Za-z0-9_.\? ! ]/';
        $new_filename= preg_replace($pattern,'',$filename);
        if(!is_null($new_filename)){
            $filename=str_replace(' ','_',$new_filename);
        }
        return $filename;
    }
	
	public function get_report_types(){
		$data=array();
		$this->db->order_by('report_type_code','ASC');
		$query=$this->db->get('report_types');
		if($query){
			$result=$query->result_array();
			foreach($result as $val){
				$data[$val['report_type_code']]=$val['report_type_desc'];
			}
		}
		return $data;
	}
	
	public function get_report_component(){
		$data=array();
		$query=$this->db->get('report_components');
		if($query){
			$result=$query->result_array();
			foreach($result as $val){
				$data[$val['id']]=$val['report_component_code'];
			}
		}
		return $data;
	}
    
	public function save($data=array()){
		$return=array();
		$message='';
		$success=false;
		if((isset($data['id'])&&$data['id']=='')||!isset($data['id'])){
			//Jika belum ada id, lakukan perintah insert

			$success=$this->db->insert($this->_table,$data);
			if($success){
				$return['id']=$this->db->insert_id();
				$message="Data saved";
			}else{
				$return['id']='';
				$message="Cannot save data. Please try again";
			}
			
		}else{
			//Jika sudah ada id, lakukan update
			$success=$this->db->update($this->_table,$data,array('id'=>$data['id']));
			$return['id']=$data['id'];
			$message="Data was successfully updated";
		}
		$return['success']=$success;
		$return['message']=$message;
		return $return;
	}	
    
	function get_data($report_id=null,$mode='object'){
		$data=array();
		if($report_id){
			$this->db->where('id',$report_id);
			$query=$this->db->get($this->_table);
			if($mode=='array'){
				$result=$query->result_array();	
			}else{
				$result=$query->result();
			}
			if(!empty($result)){
				$data=$result[0];
			}
		}
		return $data;
	}
	
	function get_data_report($report_type,$trperizinan_id, $tmpermohonan_id = null){
		$data=array();
		$ret=array();

        ## BEGIN - Added 13 August 2015
        $trunitkerja_id = null;
        if(!is_null($tmpermohonan_id) && $tmpermohonan_id != ''){
            $getPermohonan = $this->db->select('trunitkerja_id')->where('id',$tmpermohonan_id)->get('tmpermohonan');
            if($getPermohonan->num_rows() > 0){
                $resultPermohonan = $getPermohonan->result();
                $dataPermohonan = $resultPermohonan[0];
                $trunitkerja_id = $dataPermohonan->trunitkerja_id;
            }
        }
        if(!is_null($trunitkerja_id) && $trunitkerja_id != ''){
            $this->db->where('trunitkerja_id', $trunitkerja_id);
        }
        ## END - Added 13 August 2015

		$this->db->where('report_type',$report_type);
		$this->db->where('trperizinan_id',$trperizinan_id);
		$this->db->where('del_flag',0);
		$query=$this->db->get($this->_table);
		$data=$query->result_array();
		if(isset($data[0])){
			$ret= $data[0];
		}
		return $ret;
	}
	
	function get_perizinan_list(){
		$data=array();
		$this->db->select('id,n_perizinan');
        $this->db->where("id IN (SELECT trperizinan_id FROM trperizinan_user WHERE trperizinan_user.user_id = {$this->session->userdata('id_auth')})");
        $query=$this->db->get('trperizinan');
		$query_result=$query->result();
		foreach($query_result as $perizinan){
			$data[$perizinan->id]=$perizinan->n_perizinan;
		}
//		echo "<pre>";print_r($data);exit();
		return $data;
	}
	
	function get_group_query($report_type,$trperizinan_id, $tmpermohonan_id = null){
		$group_datas=array();

        ## BEGIN - Added 13 August 2015
        $trunitkerja_id = null;
        if(!is_null($tmpermohonan_id) && $tmpermohonan_id != ''){
            $getPermohonan = $this->db->select('trunitkerja_id')->where('id',$tmpermohonan_id)->get('tmpermohonan');
            if($getPermohonan->num_rows() > 0){
                $resultPermohonan = $getPermohonan->result();
                $dataPermohonan = $resultPermohonan[0];
                $trunitkerja_id = $dataPermohonan->trunitkerja_id;
            }
        }
        if(!is_null($trunitkerja_id) && $trunitkerja_id != ''){
            $this->db->where('trunitkerja_id', $trunitkerja_id);
        }
        ## END - Added 13 August 2015

		$this->db->where('report_type',$report_type);
		$this->db->where('trperizinan_id',$trperizinan_id);
		$this->db->where('del_flag',0);
		$query=$this->db->get($this->_table);
		$data=$query->result();
		if(!empty($data)){
			$id_report_generator=$data[0]->id;
			//ambil query dari group
			$this->db->where('report_generator_id',$id_report_generator);
			$query_group=$this->db->get('report_group_datas');
			$group_datas=$query_group->result_array();
		
		}
		return $group_datas;
	}

    function getQueries($reportGeneratorId){
        $group_datas=array();
        $this->db->where('id',$reportGeneratorId);
        $query=$this->db->get($this->_table);
        $data=$query->result();
        if(!empty($data)){
            $id_report_generator=$data[0]->id;
            //ambil query dari group
            $this->db->where('report_generator_id',$reportGeneratorId);
            $query_group=$this->db->get('report_group_datas');
            $group_datas=$query_group->result_array();

        }
        return $group_datas;
    }
	
	function no_report_setting_exists($report_type='',$trperizinan_id,$report_generator_id, $trunitkerja_id){
		$return=array();
		$status=true;
		$message='';
		
		$this->db->where('trunitkerja_id',$trunitkerja_id);
		$this->db->where('trperizinan_id',$trperizinan_id);
		$this->db->where('report_type',$report_type);
		if($report_generator_id!=''){
			$this->db->where('id !=',$report_generator_id);
		}
		$this->db->where('del_flag',0);
		
		$query_check=$this->db->get($this->_table);
		if($query_check){
			$query_result=$query_check->result_array();
			if(isset($query_result[0])){
				$status=false;
				$message='Tidak dapat menyimpan data, sudah ada setting dengan perizinan dan tipe laporan yang sama dengan ID "'.$query_result[0]['report_code'].'"';
			}else{
				$status=true;
				$message='';
			}
		}else{
			$status=false;
			$message='Terjadi error ketika melakukan pengecekan ke database';
		}
		$return['status']=$status;
		$return['message']=$message;
		return $return;
	}

    function no_same_id_exists($reportCode='', $report_generator_id){
        $return=array();
        $status=true;
        $message='';

        $this->db->where('report_code',$reportCode);
        if($report_generator_id!=''){
            $this->db->where('id !=',$report_generator_id);
        }

        $query_check=$this->db->get($this->_table);
        if($query_check){
            $query_result=$query_check->result_array();
            if(isset($query_result[0])){
                $status=false;
                $message='Tidak dapat menyimpan data, sudah ID ini sudah pernah digunakan';
            }else{
                $status=true;
                $message='';
            }
        }else{
            $status=false;
            $message='Terjadi error ketika melakukan pengecekan ke database';
        }
        $return['status']=$status;
        $return['message']=$message;
        return $return;
    }
	
	function delete($report_generator_id = ''){
		if($report_generator_id !=''){
			$data=array('del_flag'=>1);
			return $this->db->update($this->_table,$data,array('id'=>$report_generator_id));
		}
	}

}