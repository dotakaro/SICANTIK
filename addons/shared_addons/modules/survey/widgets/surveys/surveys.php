<?php defined('BASEPATH') or exit('No direct script access allowed');

class Widget_Surveys extends Widgets {
	
	public $title = 'Survey Widget';
	public $description = 'Widget untuk menampilkan Survey.';
	public $author = 'Indra';
	public $website = 'http://www.indra.com/';
	public $version = '0.1';
	public $fields = array(
		array(
			'field'		=> 'survey_id',
			'label'		=> 'Survey',
			'rules'		=> 'required'
		)
	);

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		// Load models
		$this->load->model('survey/survey_m');
		$this->load->model('survey/survey_question_m');
		$this->load->model('survey/survey_option_m');
		$this->load->model('survey/survey_voter_m');
		$this->load->model('survey/survey_answer_m');

		// Load language file
		$this->lang->load('survey/survey');
	}

	/**
	 * Run widget
	 *
	 * @access public
	 * @return array
	 */
	public function run($options)
	{
		// Get poll ID
        $survey_id = $options['survey_id'];

		// Get poll data
		$data = $this->survey_m->retrieve_survey($survey_id);

		// If this poll exists
		if ($data)
		{
			// Has user alread voted in this poll?
			$data['already_voted'] = $this->survey_voter_m->already_voted($survey_id);
			// Set input type
//			$data['input_type'] = $data['type'] == 'single' ? 'radio' : 'checkbox';

			// Get options
//			$data['poll_options'] = $this->poll_options_m->retrieve_poll_options($poll_id);

			// Get total votes
//			$data['total_votes'] = $this->poll_options_m->get_total_votes($poll_id);

			// Send data
			return $data;
		}

		return FALSE;
	}

	/**
	 * Display form
	 *
	 * @access public
	 * @return array
	 */
	public function form($options)
	{
		// Get all [active] Survey
        $list_survey = array();
        $surveys = $this->survey_m->retrieve_surveys(TRUE);
        if(!empty($surveys)){
            foreach($surveys as $key=>$survey){
                $list_survey[$survey['id']] = $survey['description'];
            }
        }
        $options['survey_id'] = isset($options['survey_id'])?$options['survey_id']:null;
		return array(
            'list_survey' => $list_survey,
            $options
        );
	}
	
}