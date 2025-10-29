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
class download_list_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'download_list';
		 $this->load->model('files/file_folders_m');
		 $this->load->library('files/files');
		 $this->folder = $this->file_folders_m->get_by('name', 'download_list');
	}

	//create a new item
	public function create($input)
	{
		 $fileinput = Files::upload($this->folder->id, FALSE, 'file_download');
		$to_insert = array(
			'file_desc' => $input['file_desc'],
			'published' => $input['published'],
			'created'=>date('Y-m-d H:i:s')
		);
		
		if ($fileinput['status']) {
		 	$to_insert['file_download'] = $fileinput['data']['id'];
		 }else{
			$this->session->set_flashdata('notice', $fileinput['message']);
			return false;
		}

		return $this->db->insert('download_list', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		 $fileinput = Files::upload($this->folder->id, FALSE, 'file_download');
		$to_insert = array(
'file_desc' => $input['file_desc'],
'published' => $input['published'],
'updated'=>date('Y-m-d H:i:s')
		);

		 if ($fileinput['status']) {
		 	$to_insert['file_download'] = $fileinput['data']['id'];
		 }else{
			$this->session->set_flashdata('notice', $fileinput['message']);
			return false;
		}

		return $this->db->where('id', $id)->update('download_list', $to_insert);
	}
}
