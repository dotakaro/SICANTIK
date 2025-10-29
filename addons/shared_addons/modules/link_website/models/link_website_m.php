<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Mengisi Link Website
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class link_website_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'link_website';
		// $this->load->model('files/file_folders_m');
		// $this->load->library('files/files');
		// $this->folder = $this->file_folders_m->get_by('name', 'link_website');
	}

	//create a new item
	public function create($input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			// 'fileinput' => json_encode($fileinput);
			'nama_link' => $input['nama_link'],
'url_link' => $input['url_link'],
'desc_link' => $input['desc_link'],

		);

		return $this->db->insert('link_website', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'nama_link' => $input['nama_link'],
'url_link' => $input['url_link'],
'desc_link' => $input['desc_link'],

		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('link_website', $to_insert);
	}
}
