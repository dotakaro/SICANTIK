<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk memanage File-file yang dapat didownload oleh Visitor Website
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class daftar_layanan_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'daftar_layanan';
		 $this->load->model('files/file_folders_m');
		 $this->load->library('files/files');
		 $this->folder = $this->file_folders_m->get_by('name', 'daftar_layanan');
	}

	//create a new item
	public function create($input)
	{
		 $fileinput = Files::upload($this->folder->id, FALSE, 'file_download');
		$to_insert = array(
			'file_desc' => $input['file_desc'],
			'jenis_izin' => $input['jenis_izin'],
            'jenis_file' => $input['jenis_file'],
			'nama_perizinan' => $input['nama_perizinan'],
			'published' => $input['published'],
			'created'=>date('Y-m-d H:i:s')
		);
		
		if ($fileinput['status']) {
		 	$to_insert['file_download'] = $fileinput['data']['id'];
		 }else{
			$this->session->set_flashdata('notice', $fileinput['message']);
			return false;
		}

		return $this->db->insert('daftar_layanan', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{

		$to_insert = array(
			'file_desc' => $input['file_desc'],
			'jenis_izin' => $input['jenis_izin'],
            'jenis_file' => $input['jenis_file'],
			'nama_perizinan' => $input['nama_perizinan'],
			'published' => $input['published'],
			'updated'=>date('Y-m-d H:i:s')
		);

        if(!empty($_FILES['file_download']['name'])){
            $fileinput = Files::upload($this->folder->id, FALSE, 'file_download');
             if ($fileinput['status']) {
                 $this->Files = new Files();
                 $deleted_file = $this->get($id);
                 $this->Files->delete_file($deleted_file->file_download);

                $to_insert['file_download'] = $fileinput['data']['id'];
             }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

		return $this->db->where('id', $id)->update('daftar_layanan', $to_insert);
	}
        
        function get_download_list($id_jenis_izin){
            $downloads = array();
            $downloads['formulir'] = $this->db->where('jenis_izin',$id_jenis_izin)->where('jenis_file','Formulir')->get('default_daftar_layanan')->result();
            $downloads['dasar_hukum'] = $this->db->where('jenis_izin',$id_jenis_izin)->where('jenis_file','Dasar Hukum')->get('default_daftar_layanan')->result();
            return $downloads;
        }
        
        function get_all_download(){
            $data_download = array();
            $data_group = $this->db->query(
                    "SELECT DISTINCT(nama_perizinan)as nama_perizinan FROM default_daftar_layanan 
                        WHERE published = 1")->result();
            if(!empty($data_group)){
                foreach($data_group as $index=>$group){
                    $download_list = $this->db->query(
                        "SELECT  * FROM default_daftar_layanan 
                            WHERE nama_perizinan = \"{$group->nama_perizinan}\" 
                            ORDER BY created DESC")->result();
                    $data_download[$index]['nama_perizinan']= $group->nama_perizinan;        
                    $data_download[$index]['list'] = $download_list;
                }
            }
            return $data_download;
        }

    function get_all_dasar_hukum($limit,$offset){
        $data = array();
        $data['rows'] = $this->db->where('jenis_file','Dasar Hukum')
            ->order_by('nama_perizinan','ASC')
            ->order_by('created','DESC')
            ->limit($limit,$offset)
            ->get('default_daftar_layanan')->result();

        //count query menghitung total semua dasar hukum
        $q=$this->db->select('COUNT(*) as count',FALSE)
            ->from('default_daftar_layanan')
            ->where('jenis_file','Dasar Hukum');
        $tmp=$q->get()->result();
        $data['num_rows']=$tmp[0]->count;

        return $data;
    }

    function get_all_formulir($limit,$offset){
        $data = array();
        $data['rows'] = $this->db->where('jenis_file','Formulir')
            ->order_by('nama_perizinan','ASC')
            ->order_by('created','DESC')
            ->limit($limit,$offset)
            ->get('default_daftar_layanan')->result();

        //count query menghitung total semua dasar hukum
        $q=$this->db->select('COUNT(*) as count',FALSE)
            ->from('default_daftar_layanan')
            ->where('jenis_file','Formulir');
        $tmp=$q->get()->result();
        $data['num_rows']=$tmp[0]->count;

        return $data;
    }
}
