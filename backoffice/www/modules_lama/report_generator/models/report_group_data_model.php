<?php
class Report_group_data_model extends Model{
    private $_table="report_group_datas";
	private $_related_tables=array('report_tables','report_fields','report_conditions');
	
    public function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
    }
	
	public function save($data=array()){
		$return=array();
		$message='';
		$success=false;
		if(isset($data['report_group_code'])){
			$data['report_group_code']=str_replace(' ','_',$data['report_group_code']);
		}
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
	
	public function save_all($datas=array(),$report_generator_id=null,$type=null){
		$return=array();
//		echo "<pre>";print_r($datas);exit();
		$x=0;
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
			if(isset($data['report_group_code'])){
				$data['report_group_code']=str_replace(' ','_',$data['report_group_code']);
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
			
			$x++;
		}
		return $return;
	}
	
	public function delete($report_group_data_id=null){
		$result=false;
		if($report_group_data_id){
			$this->db->where('id',$report_group_data_id);
			if($this->db->delete($this->_table)){$result=true;}
			foreach($this->_related_tables as $table_name){
				$this->db->where('report_group_data_id',$report_group_data_id);
				$this->db->delete($table_name);
			}
		}
		return $result;
	}
	
	function get_all($report_generator_id=null,$mode='object'){
		$data=array();
		$ret=array();
		$count=0;	
		if($report_generator_id){
			$this->db->where('report_generator_id',$report_generator_id);
			$query=$this->db->get($this->_table);
			if($mode=='array'){
				$data=$query->result_array();
			}else{
				$data=$query->result();
			}
			$this->db->where('report_generator_id',$report_generator_id);
			$this->db->from($this->_table);
			$count=$this->db->count_all_results();			
		}
		$ret['rows']=$data;
		$ret['num_rows']=$count;
		return $ret;
	}
	
	function get_data($report_group_data_id=null){
		$data=array();
		if($report_group_data_id){
			$this->db->where('id',$report_group_data_id);
			$query=$this->db->get($this->_table);
			$data=$query->result();
		}
		return $data[0];
	}

}