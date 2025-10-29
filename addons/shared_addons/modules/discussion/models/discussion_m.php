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
class discussion_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'discussion';
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
			'topic' => $input['topic'],
            'message_to' => $input['message_to'],
            'created_by' => $input['created_by'],
            'created'=>date('Y-m-d H:i:s')
		);

		return $this->db->insert('discussion', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'topic' => $input['topic'],
'message_to' => $input['message_to'],
'created_by' => $input['created_by'],
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('discussion', $to_insert);
	}

    public function delete_comment($id){
        return $this->db->where('discussion_id', $id)->delete('discussion_comment');
    }
}
