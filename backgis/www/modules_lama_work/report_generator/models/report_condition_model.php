<?php
class Report_condition_model extends Model{
    private $_table="report_conditions";
	
    public function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
    }
	
	public function save_all($datas=array(),$report_generator_id=null,$report_group_data_id=null,$type=null){
		$return=array();
//		echo "<pre>";print_r($datas);exit();
		$x=0;
		$overall_success=false;
		foreach($datas as $data){
			$message='';
			$success=false;
			$id='';
			
			if($type=='insert'){
				unset($data['id']);
			}
			if($report_generator_id){
				$data['report_generator_id']=$report_generator_id;
			}
			if($report_generator_id){
				$data['report_group_data_id']=$report_group_data_id;
			}		
						
			if((isset($data['id'])&&$data['id']=='')||!isset($data['id'])){
				//Jika belum ada id, lakukan perintah insert
				$success=$this->db->insert($this->_table,$data);
				if($success){
					$id=$this->db->insert_id();
					$message="Data saved";
				}else{
					$id='';
					$message="Cannot save data. Please try again";
				}
				
			}else{
				//Jika sudah ada id, lakukan update
				$success=$this->db->update($this->_table,$data,array('id'=>$data['id']));
				$id=$data['id'];
				$message="Data was successfully updated";
			}
			$return[$x]['id']=$id;
			$return[$x]['message']=$message;
			$return[$x]['success']=$success;
			$overall_success=$success;
			$x++;
		}
		$return['overall_success']=$overall_success;
		return $return;
	}
	
	public function delete($report_condition_id=null){
		$result=false;
		if($report_condition_id){
			$this->db->where('id',$report_condition_id);
			if($this->db->delete($this->_table)){$result=true;}
		}
		return $result;
	}
	
	function get_all($report_group_data_id=null,$mode='object',$only_condition=true){
		$data=array();
		$ret=array();
		$count=0;	
		if($report_group_data_id){
			$this->db->where('report_group_data_id',$report_group_data_id);
			if($only_condition){
				$this->db->where('report_table','');
			}
			$query=$this->db->get($this->_table);
			if($mode=='array'){
				$data=$query->result_array();
			}else{
				$data=$query->result();
			}			
			
			$this->db->where('report_group_data_id',$report_group_data_id);
			if($only_condition){
				$this->db->where('report_table','');
			}
			$this->db->from($this->_table);
			$count=$this->db->count_all_results();			
		}
		$ret['rows']=$data;
		$ret['num_rows']=$count;
		return $ret;
	}		
	
	function get_all_report_condition($report_group_data_id=null, $report_generator_id=null){
		$data=array();
		$ret=array();
		$count=0;	
		if($report_group_data_id){
			$this->db->where('report_group_data_id',$report_group_data_id);
			$this->db->where('report_generator_id',$report_generator_id);
			$this->db->where('report_table',"");
			$query=$this->db->get($this->_table);
			$data=$query->result();
			
			$this->db->where('report_group_data_id',$report_group_data_id);
			$this->db->where('report_generator_id',$report_generator_id);
			$this->db->where('report_table',"");
			$this->db->from($this->_table);
			$count=$this->db->count_all_results();			
		}
		$ret['rows']=$data;
		$ret['num_rows']=$count;
		return $ret;
	}	
	
	function get_join_conditions($report_generator_id,$report_group_data_id,$table_name){
		$data=array();
		$ret=array();
		$count=0;
//		echo $table_name;exit();
		if($report_group_data_id){
			$this->db->where('report_generator_id',$report_generator_id);
			$this->db->where('report_group_data_id',$report_group_data_id);
			$this->db->where('report_table',$table_name);
			$query=$this->db->get($this->_table);
			$data=$query->result();
			
			$this->db->where('report_generator_id',$report_generator_id);
			$this->db->where('report_group_data_id',$report_group_data_id);
			$this->db->where('report_table',$table_name);
			$this->db->from($this->_table);
			$count=$this->db->count_all_results();			
		}
		$ret['rows']=$data;
		$ret['num_rows']=$count;
		return $ret;
	}
	
	/*function get_data($report_group_data_id=null){
		$data=array();
		if($report_group_data_id){
			$this->db->where('id',$report_group_data_id);
			$query=$this->db->get($this->_table);
			$data=$query->result();
		}
		return $data[0];
	}*/

}