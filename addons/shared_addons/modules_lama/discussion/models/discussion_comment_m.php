<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Diskusi di Backend
 *
 * @author 		Indra Halm
 * @website		http://indra.com
 * @package 	com.indra.pyro.discussion
 * @subpackage 	
 * @copyright 	MIT
 */
class discussion_comment_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'discussion_comment';
		// $this->load->model('files/file_folders_m');
		// $this->load->library('files/files');
		// $this->folder = $this->file_folders_m->get_by('name', 'discussion');
	}

	//create a new item
	public function create($input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			// 'fileinput' => json_encode($fileinput);
			'comment' => $input['comment'],
            'discussion_id' => $input['discussion_id'],
            'created_by' => $input['created_by'],
            'created'=>date('Y-m-d H:i:s')
		);

		return $this->db->insert('discussion_comment', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'comment' => $input['comment'],
            'discussion_id' => $input['discussion_id'],
            'created_by' => $input['created_by'],
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('discussion_comment', $to_insert);
	}
}
