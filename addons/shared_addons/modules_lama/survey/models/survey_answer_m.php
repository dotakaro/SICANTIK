<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Module untuk Create Pertanyaan Survey
 *
 * @author       Indra Halim
 * @website      http://indra.com
 * @package      com.indra.survey.question
 * @subpackage   
 * @copyright    MIT
 */
class survey_answer_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'survey_answers';
		// $this->load->model('files/file_folders_m');
		// $this->load->library('files/files');
		// $this->folder = $this->file_folders_m->get_by('name', 'survey_question');
	}

	//create a new item
	public function create($input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			// 'fileinput' => json_encode($fileinput);
			'answer' => $input['answer'],
            'survey_question_id' => $input['survey_question_id'],
            'survey_voter_id'=>$input['survey_voter_id']
		);

		return $this->db->insert('survey_answers', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
            'answer' => $input['answer'],
            'survey_question_id' => $input['survey_question_id'],
            'survey_voter_id'=>$input['survey_voter_id']
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('survey_answers', $to_insert);
	}

    public function get_insert_id(){
        return $this->db->insert_id();
    }

}
