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
	
function i_kode_kbli($tmpermohonan_id){
	$i_kode_kblis = "";
	$query = "SELECT a.v_property
 FROM tmproperty_jenisperizinan a 
 INNER JOIN tmproperty_jenisperizinan_trproperty b 
 ON a.id=b.tmproperty_jenisperizinan_id 
INNER JOIN trproperty c 
 ON b.trproperty_id=c.id 
INNER JOIN tmpermohonan d 
 ON a.pendaftaran_id=d.pendaftaran_id 
INNER JOIN tmpermohonan_trperizinan e 
 ON d.id= e.tmpermohonan_id 
INNER JOIN trperizinan_trproperty f 
 ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id) 
INNER JOIN trproperty g ON f.c_parent=g.id 
 WHERE d.id=$tmpermohonan_id AND f.c_sk_id=1 AND  c.short_name = 'kbli'";

	//$query = $this->db->get();
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row{0};
/*	if($query){
			$data=$query->result();
			if(!empty($data)){
				$i_kode_kblis=$data[0];
			}
	}
        return $i_kode_kblis;
*/	
}

function fungsi_izin($tmpermohonan_id){
	$fungsi_izins = "";
	$query = "SELECT a.v_property
 FROM tmproperty_jenisperizinan a 
 INNER JOIN tmproperty_jenisperizinan_trproperty b 
 ON a.id=b.tmproperty_jenisperizinan_id 
INNER JOIN trproperty c 
 ON b.trproperty_id=c.id 
INNER JOIN tmpermohonan d 
 ON a.pendaftaran_id=d.pendaftaran_id 
INNER JOIN tmpermohonan_trperizinan e 
 ON d.id= e.tmpermohonan_id 
INNER JOIN trperizinan_trproperty f 
 ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id) 
INNER JOIN trproperty g ON f.c_parent=g.id 
 WHERE d.id=$tmpermohonan_id AND f.c_sk_id=1 AND  c.short_name = 'kodefungsi'";

	//$query = $this->db->get();
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row{0};
/*	if($query){
			$data=$query->result();
			if(!empty($data)){
				$i_kode_kblis=$data[0];
			}
	}
        return $i_kode_kblis;
*/	
}

function no_lama($tmpermohonan_id){
	$no_lamas = "";
	$query = "SELECT
a.no_surat
FROM tmsk a
INNER JOIN tmpermohonan_tmsk b ON a.id=b.tmsk_id
INNER JOIN tmpermohonan c ON c.id_lama=b.tmpermohonan_id
WHERE c.id =$tmpermohonan_id";

	//$query = $this->db->get();
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row{0};
/*	if($query){
			$data=$query->result();
			if(!empty($data)){
				$i_kode_kblis=$data[0];
			}
	}
        return $i_kode_kblis;
*/	
}

function fungsi_sbu($tmpermohonan_id){
	$fungsi_sbus = "";
	$query = "SELECT a.v_property
 FROM tmproperty_jenisperizinan a 
 INNER JOIN tmproperty_jenisperizinan_trproperty b 
 ON a.id=b.tmproperty_jenisperizinan_id 
INNER JOIN trproperty c 
 ON b.trproperty_id=c.id 
INNER JOIN tmpermohonan d 
 ON a.pendaftaran_id=d.pendaftaran_id 
INNER JOIN tmpermohonan_trperizinan e 
 ON d.id= e.tmpermohonan_id 
INNER JOIN trperizinan_trproperty f 
 ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id) 
INNER JOIN trproperty g ON f.c_parent=g.id 
 WHERE d.id=$tmpermohonan_id AND f.c_sk_id=1 AND  c.short_name = 'kodesbu'";

	//$query = $this->db->get();
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row{0};
/*	if($query){
			$data=$query->result();
			if(!empty($data)){
				$i_kode_kblis=$data[0];
			}
	}
        return $i_kode_kblis;
*/	
}

function kode_gol($tmpermohonan_id){
	$kode_gols = "";
	$query = "SELECT a.v_property
 FROM tmproperty_jenisperizinan a 
 INNER JOIN tmproperty_jenisperizinan_trproperty b 
 ON a.id=b.tmproperty_jenisperizinan_id 
INNER JOIN trproperty c 
 ON b.trproperty_id=c.id 
INNER JOIN tmpermohonan d 
 ON a.pendaftaran_id=d.pendaftaran_id 
INNER JOIN tmpermohonan_trperizinan e 
 ON d.id= e.tmpermohonan_id 
INNER JOIN trperizinan_trproperty f 
 ON (b.trproperty_id=f.trproperty_id AND f.trperizinan_id=e.trperizinan_id) 
INNER JOIN trproperty g ON f.c_parent=g.id 
 WHERE d.id=$tmpermohonan_id AND f.c_sk_id=1 AND  c.short_name = 'kodegolongan'";

	//$query = $this->db->get();
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row{0};
/*	if($query){
			$data=$query->result();
			if(!empty($data)){
				$i_kode_kblis=$data[0];
			}
	}
        return $i_kode_kblis;
*/	
}

function fungsi_penomoran($tmpermohonan_id){
	$fungsi_penomorans = "";
	$query = "SELECT
trformatpenomoran.format
FROM
tmpermohonan AS A
INNER JOIN tmpermohonan_trperizinan AS B ON B.tmpermohonan_id = A.id
INNER JOIN trperizinan AS C ON B.trperizinan_id = C.id
INNER JOIN tmpemohon_tmpermohonan AS D ON D.tmpermohonan_id = A.id
INNER JOIN tmpemohon AS E ON D.tmpemohon_id = E.id
INNER JOIN tmpermohonan_trjenis_permohonan AS F ON F.tmpermohonan_id = A.id
INNER JOIN trjenis_permohonan AS G ON F.trjenis_permohonan_id = G.id
INNER JOIN trformatpenomoran ON trformatpenomoran.id_perizinan = B.trperizinan_id AND G.id = trformatpenomoran.id_jenis
WHERE
A.id = $tmpermohonan_id";

	//$query = $this->db->get();
	$result = mysql_query($query);
	$row = mysql_fetch_array($result);
	return $row{0};
/*	if($query){
			$data=$query->result();
			if(!empty($data)){
				$i_kode_kblis=$data[0];
			}
	}
        return $i_kode_kblis;
*/	
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
	private function _translate_format($number_format,$sequence,$trperizinan_id,$tmpermohonan_id){
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
				$new_element=str_replace('KK',substr($this->i_kode_kbli($tmpermohonan_id),0,2),$new_element);
				$new_element=str_replace('FG',$this->fungsi_izin($tmpermohonan_id),$new_element);
				$new_element=str_replace('KB',$this->kode_gol($tmpermohonan_id),$new_element);
				$new_element=str_replace('SBU',$this->fungsi_sbu($tmpermohonan_id),$new_element);
				$new_element=str_replace('KDPER',$this->fungsi_penomoran($tmpermohonan_id),$new_element);
				$new_element=str_replace('NOLAMA',$this->no_lama($tmpermohonan_id),$new_element);
				//echo $this->i_kode_kbli($tmpermohonan_id);
				//echo $new_element.'<br>';
				#####################################
				
				### Mengganti kode nomor urut surat sesuai dengan panjang karakternya ###
				$seq_length=substr_count($new_element,'N');//Panjang karakter nomor urut
				$seq_format=str_repeat('N',$seq_length);
				$padded_seq=str_pad($sequence,$seq_length,'0',STR_PAD_LEFT);
				$new_element=str_replace($seq_format,$padded_seq,$new_element);
				#########################################################################
				
				###### KBLI###
				//$kode_kbli=str_replace('KK', substr($this->i_kode_kbli(),0,2));
				###End of KBLI###
				
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
			$new_number=$this->_translate_format($component_setting['format_nomor'],$last_sequence,$trperizinan_id,$tmpermohonan_id);
			if($new_number!=''){
				//Jika nomor ada hasilnya, lakukan proses update sequence terakhir ke database
				$update_data=array();
				$update_data['id']=$component_setting['id'];
				$update_data['last_num_seq']=$last_sequence;
				$this->save($update_data);
//				echo $new_number;exit();
				$component_setting['format_nomor']=$new_number;
				//Nomor Selesai disini
				
//				$this->load->plugins('ciqrcode');

				//$params['data'] = "$base_url_websevices/perizinan_online/tracking_perizinan?no_pendaftaran="base64_encode($component_setting['format_nomor']);
//				$params['level'] = 'H';
//				$params['size'] = 10;
//				$params['savename'] = FCPATH.'tes.png';
//				$this->ciqrcode->generate($params);
//echo '<img src="'.base_url().'tes.png" />';
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