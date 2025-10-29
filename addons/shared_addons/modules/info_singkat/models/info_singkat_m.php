<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Info Singkat
 *
 * @author 		Info Singkat
 * @website		
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class info_singkat_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'info_singkat';
		// $this->load->model('files/file_folders_m');
		// $this->load->library('files/files');
		// $this->folder = $this->file_folders_m->get_by('name', 'info_singkat');
	}

	//create a new item
	public function create($input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			// 'fileinput' => json_encode($fileinput);
			'isi_info' => $input['isi_info'],
'published' => $input['published'],
'created'=>date('Y-m-d H:i:s')
		);

		return $this->db->insert('info_singkat', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'isi_info' => $input['isi_info'],
'published' => $input['published'],
'modified'=>date('Y-m-d H:i:s')
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('info_singkat', $to_insert);
	}
}
