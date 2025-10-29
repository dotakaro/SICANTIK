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
class survey_question_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'survey_questions';
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
			'question_desc' => $input['question_desc'],
            'question_type' => $input['question_type'],
            'multiple_votes' => (isset($input['multiple_votes']))?1:0,
            'survey_id'=>$input['survey_id']
		);

		return $this->db->insert('survey_questions', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'question_desc' => $input['question_desc'],
            'question_type' => $input['question_type'],
            'multiple_votes' => (isset($input['multiple_votes']))?1:0,
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('survey_questions', $to_insert);
	}

    public function get_insert_id(){
        return $this->db->insert_id();
    }


}
