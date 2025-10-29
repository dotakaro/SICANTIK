<?php
class Report_subreport_model extends Model{
    private $_table="report_subreports";
	
    public function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
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
	
	function delete_all($report_generator_id=null){
		$success=false;
		if($report_generator_id){
			$this->db->where('report_generator_id',$report_generator_id);
			$success=$this->db->delete($this->_table);			
		}
		return $success;
	}

}