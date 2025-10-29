<?php
class Report_filter_model extends Model{
    private $_table="report_filters";

    public function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
    }

    public function save_all($datas=array(),$report_generator_id=null,$report_group_data_id=null,$type=null){
        $return=array();
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

    public function delete($report_filter_id=null){
        $result=false;
        if($report_filter_id){
            $this->db->where('id',$report_filter_id);
            if($this->db->delete($this->_table)){$result=true;}
        }
        return $result;
    }

    function get_all($report_group_data_id=null,$mode='object'){
        $data=array();
        $ret=array();
        $count=0;
        if($report_group_data_id){
            $this->db->where('report_group_data_id',$report_group_data_id);
            $this->db->order_by('filter_no','ASC');
            $this->db->order_by('id','ASC');

            $query=$this->db->get($this->_table);
            if($mode=='array'){
                $data=$query->result_array();
            }else{
                $data=$query->result();
            }

            $this->db->where('report_group_data_id',$report_group_data_id);

            $this->db->from($this->_table);
            $count=$this->db->count_all_results();
        }
        $ret['rows']=$data;
        $ret['num_rows']=$count;
        return $ret;
    }

    function get_all_by_report_generator($reportGeneratorId=null,$mode='object'){
        $data=array();
        $ret=array();
        $count=0;
        if($reportGeneratorId){
            //Ambil data
            $this->db->where('report_generator_id',$reportGeneratorId);
            $this->db->where('filter_name IS NOT NULL');
            $this->db->order_by('filter_no','ASC');
            $this->db->order_by('id','ASC');
            $query=$this->db->get($this->_table);
            if($mode=='array'){//JIka return yang diharapkan adalah array biasa
                $data=$query->result_array();
                if(!empty($data)){
                    foreach($data as $index=>$filter){
                        switch($filter['filter_type']){
                            case 'multi_dropdown'://Khusus dropdown, jalankan query untuk produce list dropdown
                            case 'single_dropdown'://Khusus dropdown, jalankan query untuk produce list dropdown
                                $newData = $filter;

                                ## BEGIN - Execute SQL Query untuk filter dropdown
                                $sqlQuery = $newData['query_filter'];
                                $this->db->query($sqlQuery);
                                $runQuery=$this->db->query($sqlQuery);
                                $result = $runQuery->result();
                                ## END - Execute SQL Query untuk filter dropdown

                                $newData['filter_result'] = $result;
                                $data[$index] = $newData;
                                break;
                            default:
                                $newData = $filter;
                                $newData['filter_result'] = array();
                                $data[$index] = $newData;
                                break;
                        }
                    }
                }
            }else{//Jika return yang diharapkan adalah array object
                $data=$query->result();
                if(!empty($data)){
                    foreach($data as $index=>$filter){
                        switch($filter->filter_type){
                            case 'multi_dropdown'://Khusus dropdown, jalankan query untuk produce list dropdown
                            case 'single_dropdown'://Khusus dropdown, jalankan query untuk produce list dropdown
                                $newData = $filter;

                                ## BEGIN - Execute SQL Query untuk filter dropdown
                                $sqlQuery = $newData->query_filter;
                                $this->db->query($sqlQuery);
                                $runQuery=$this->db->query($sqlQuery);
                                $result = $runQuery->result();
                                ## END - Execute SQL Query untuk filter dropdown

                                $newData->filter_result = $result;
                                $data[$index] = $newData;
                                break;
                            default:
                                $newData = $filter;
                                $newData->filter_result = new stdClass();
                                $data[$index] = $newData;
                                break;
                        }
                    }
                }
            }

            //Ambil jumlah baris
            $this->db->where('report_generator_id',$reportGeneratorId);
            $this->db->from($this->_table);
            $count=$this->db->count_all_results();
        }
        $ret['rows']=$data;
        $ret['num_rows']=$count;
        return $ret;
    }

    function get_all_report_filter($report_group_data_id=null, $report_generator_id=null){
        $data=array();
        $ret=array();
        $count=0;
        if($report_group_data_id){
            $this->db->where('report_group_data_id',$report_group_data_id);
            $this->db->where('report_generator_id',$report_generator_id);
            $query=$this->db->get($this->_table);
            $data=$query->result();

            $this->db->where('report_group_data_id',$report_group_data_id);
            $this->db->where('report_generator_id',$report_generator_id);
            $this->db->from($this->_table);
            $count=$this->db->count_all_results();
        }
        $ret['rows']=$data;
        $ret['num_rows']=$count;
        return $ret;
    }
}