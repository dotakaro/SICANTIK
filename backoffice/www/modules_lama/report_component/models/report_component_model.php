<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
* Model class for report component module
* @author Indra Halim
* @version 1.0
*/
class Report_component_model extends Model{
    private $_table = 'report_components';
	public $kode_bap='BAP';
	public $kode_skrd='SKRD';
	public $kode_sptl='SPTL';
	public $kode_izin='IZIN';
	public $kode_sk='SK';
	
	private $_bulan_romawi=array(
		'01'=>'I',
		'02'=>'II',
		'03'=>'III',
		'04'=>'IV',
		'05'=>'V',
		'06'=>'VI',
		'07'=>'VII',
		'08'=>'VIII',
		'09'=>'IX',
		'10'=>'X',
		'11'=>'XI',
		'12'=>'XII'
	);
	
    public function __construct() {
        parent::__construct(); // function parent
        $this->db = $this->load->database('default', TRUE);
    }
	
	public function get_report_types(){
		$data=array();
		//$this->db->where('ada_format_nomor',1);
		$this->db->order_by('report_type_code','ASC');
		$query=$this->db->get('report_types');
		if($query){
			$result=$query->result_array();
			foreach($result as $val){
                if($val['ada_format_nomor'] == 0){
                    $ket = ' - Tidak ada format nomor';
                }else{
                    $ket = '';
                }
				$data[$val['report_type_code']]=$val['report_type_desc'].'<i>'.$ket.'</i>';
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
    
	function get_data($report_component_id=null,$mode='object'){
		$data=array();
		if($report_component_id){
			$this->db->where('id',$report_component_id);
			$query=$this->db->get($this->_table);
			if($mode=='array'){
				$data=$query->result_array();	
			}else{
				$data=$query->result();
			}
			
		}
		return $data[0];
	}
	
	function delete($report_component_id = ''){
		if($report_component_id !=''){
			$data=array('del_flag'=>1);
			return $this->db->update($this->_table,$data,array('id'=>$report_component_id));
		}
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
	
	/**
	* Fungsi untuk mendapatkan data komponen report pada saat rendering report 
	* @param string $report_type
	* @param integer $trperizinan_id
	* 
	*/	
	private function _get_component_setting($report_type,$trperizinan_id, $trunitkerja_id = null){
		$component_setting=array();
		$this->db->select('id,format_nomor,last_num_seq,nama_penandatangan,jabatan,nama_kantor,nip');
        if(!is_null($trunitkerja_id) && $trunitkerja_id != ''){
            $this->db->where('trunitkerja_id', $trunitkerja_id);
        }
		$this->db->where('report_type',$report_type);
		$this->db->where('del_flag !=',1);
		$this->db->where('trperizinan_id',$trperizinan_id);
        $query=$this->db->get($this->_table);
		if($query){
			$data=$query->result_array();
			if(!empty($data)){
				$component_setting=$data[0];
			}
		}
        return $component_setting;
	}
	
	/**
	* Fungsi untuk menerjemahkan format nomor surat menjadi nomor yang siap digunakan
	* @param string $number_format
	* @param integer $sequence
	* @param integer $trperizinan_id
	* 
	*/
	private function _translate_format($number_format,$sequence,$trperizinan_id){
		$new_number='';
		
		$start = '{';
		$end = '}';
		$pattern = sprintf(
		    '/%s(.+?)%s/ims',
		    preg_quote($start, '/'), preg_quote($end, '/')
		);

		$matches=array();
		$replacement_elements=array();
		preg_match_all($pattern,$number_format,$matches);
		
		if(isset($matches[0]) && isset($matches[1])){
			foreach($matches[1] as $key=>$raw_format){
				## Menggganti kode bulan dan tahun ##
				$new_element=str_replace('DD',date('d'),$raw_format);
				$new_element=str_replace('MM',$this->_bulan_romawi[date('m')],$new_element);
				$new_element=str_replace('mm',date('m'),$new_element);
				$new_element=str_replace('YYYY',date('Y'),$new_element);
				#####################################
				
				### Mengganti kode nomor urut surat sesuai dengan panjang karakternya ###
				$seq_length=substr_count($new_element,'N');//Panjang karakter nomor urut
				$seq_format=str_repeat('N',$seq_length);
				$padded_seq=str_pad($sequence,$seq_length,'0',STR_PAD_LEFT);
				$new_element=str_replace($seq_format,$padded_seq,$new_element);
				#########################################################################
				
				### Mengganti kode jenis izin sesuai dengan panjang karakternya ###
				$perizinan_length=substr_count($new_element,'J');//Panjang karakter nomor urut
				$perizinan_format=str_repeat('J',$perizinan_length);
				$padded_perizinan=str_pad($trperizinan_id,$perizinan_length,'0',STR_PAD_LEFT);
				$new_element=str_replace($perizinan_format,$padded_perizinan,$new_element);
				#########################################################################
				$replacement_elements[$key]=$new_element;		
			}
			$new_number=str_replace($matches[0],$replacement_elements,$number_format);
		}
		return $new_number;
	}
	
	/*private function _translate_format($number_format,$sequence,$trperizinan_id){
		$new_number='';
		
		## Menggganti kode bulan dan tahun ##
		$new_number=str_replace('DD',date('d'),$number_format);
		$new_number=str_replace('MM',$this->_bulan_romawi[date('m')],$new_number);
		$new_number=str_replace('mm',date('m'),$new_number);
		$new_number=str_replace('YYYY',date('Y'),$new_number);
		#####################################
		
		### Mengganti kode nomor urut surat sesuai dengan panjang karakternya ###
		$seq_length=substr_count($new_number,'N');//Panjang karakter nomor urut
		$seq_format=str_repeat('N',$seq_length);
		$padded_seq=str_pad($sequence,$seq_length,'0',STR_PAD_LEFT);
		$new_number=str_replace($seq_format,$padded_seq,$new_number);
		#########################################################################
		
		### Mengganti kode jenis izin sesuai dengan panjang karakternya ###
		$perizinan_length=substr_count($new_number,'J');//Panjang karakter nomor urut
		$perizinan_format=str_repeat('J',$perizinan_length);
		$padded_perizinan=str_pad($trperizinan_id,$perizinan_length,'0',STR_PAD_LEFT);
		$new_number=str_replace($perizinan_format,$padded_perizinan,$new_number);
		#########################################################################
		
//		echo $new_number." | ".$trperizinan_id. " | ".$sequence.'tst';exit();
		
		return $new_number;
	}*/
	
	
	/**
	* Fungsi untuk mendapatkan nomor hasil generate serta komponen penandatangan sesuai setting yang telah ditentukan sebelumnya
	* @return array $report_component Komponen Report berisi nomor surat dan penandatangan untuk satu jenis Report dan satu izin
	*/
	public function get_report_component($report_type,$trperizinan_id, $tmpermohonan_id= null){
        $trunitkerja_id = null;
        $report_component = null;
        if(!is_null($tmpermohonan_id) && $tmpermohonan_id != ''){
            $getPermohonan = $this->db->select('trunitkerja_id')->where('id',$tmpermohonan_id)->get('tmpermohonan');
            if($getPermohonan->num_rows() > 0){
                $resultPermohonan = $getPermohonan->result();
                $dataPermohonan = $resultPermohonan[0];
                $trunitkerja_id = $dataPermohonan->trunitkerja_id;
            }
        }

		$report_component=array();
		$component_setting=$this->_get_component_setting($report_type,$trperizinan_id, $trunitkerja_id);
		if(!empty($component_setting)){
			$last_sequence=$component_setting['last_num_seq']+1;
			$new_number=$this->_translate_format($component_setting['format_nomor'],$last_sequence,$trperizinan_id);
			if($new_number!=''){
				//Jika nomor ada hasilnya, lakukan proses update sequence terakhir ke database
				$update_data=array();
				$update_data['id']=$component_setting['id'];
				$update_data['last_num_seq']=$last_sequence;
				$this->save($update_data);
//				echo $new_number;exit();
				$component_setting['format_nomor']=$new_number;
			}
			$report_component=$component_setting;
		}
        return $report_component;
	}
	
	/**
	* Fungsi untuk mendapatkan data penandatangan untuk dipassing ke jasper report
	* @return array $component_setting Komponen penandatangan untuk satu jenis Report dan satu izin
	*/
	public function get_penandatangan($report_type,$trperizinan_id, $tmpermohonan_id = null){
		$component_setting=array();

        ##BEGIN - Added 13 August 2015
        $trunitkerja_id = null;
        if(!is_null($tmpermohonan_id) && $tmpermohonan_id != ''){
            $getPermohonan = $this->db->select('trunitkerja_id')->where('id',$tmpermohonan_id)->get('tmpermohonan');
            if($getPermohonan->num_rows() > 0){
                $resultPermohonan = $getPermohonan->result();
                $dataPermohonan = $resultPermohonan[0];
                $trunitkerja_id = $dataPermohonan->trunitkerja_id;
            }
        }
        ##END - Added 13 August 2015

        $component_setting=$this->_get_component_setting($report_type,$trperizinan_id, $trunitkerja_id);
		return $component_setting;
	}
	
	function no_report_setting_exists($report_type='',$trperizinan_id,$report_component_id, $trunitkerja_id){
		$return=array();
		$status=true;
		$message='';
		
		$this->db->where('trunitkerja_id',$trunitkerja_id);
		$this->db->where('trperizinan_id',$trperizinan_id);
		$this->db->where('report_type',$report_type);
		if($report_component_id!=''){
			$this->db->where('id !=',$report_component_id);
		}
		$this->db->where('del_flag',0);
		
		$query_check=$this->db->get($this->_table);
		if($query_check){
			$query_result=$query_check->result_array();
			if(isset($query_result[0])){
				$status=false;
				$message='Tidak dapat menyimpan data, sudah ada setting dengan perizinan dan tipe laporan yang sama dengan ID "'.$query_result[0]['report_component_code'].'"';
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
	
}