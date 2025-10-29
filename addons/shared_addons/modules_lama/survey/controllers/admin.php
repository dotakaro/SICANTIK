<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Create Survey
 *
 * @author       Indra
 * @website      http://indra.com
 * @package      com.indra.survey
 * @subpackage   
 * @copyright    MIT
 */
class Admin extends Admin_Controller
{
	protected $section = 'items';

	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->library('form_validation');
		$this->lang->load('survey');
		$this->lang->load('survey_question');

		$this->load->model('survey_m');
        $this->load->model('survey_question_m');
        $this->load->model('survey_option_m');
        $this->load->model('survey_answer_m');
        $this->load->model('survey_voter_m');

        $this->template->list_question_type = array(
            'option'=>'Option',
            'freetext'=>'Free Text'
        );

		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');

		// Set the validation rules
		$this->item_validation_rules = array(
            array(
                'field' => 'slug',
                'label' => 'Slug',
                'rules' => 'required|trim',),
            array(
                'field' => 'description',
                'label' => 'Description',
                'rules' => 'required|trim',),
            array(
                'field' => 'open_date',
                'label' => 'Open_date',
                'rules' => 'required',),
            array(
                'field' => 'close_date',
                'label' => 'Close_date',
                'rules' => 'required',),
            array(
                'field' => 'active',
                'label' => 'Active',
                'rules' => '',),
		);

        // Set the validation rules
        $this->question_validation_rules = array(
            array(
                'field' => 'question_desc',
                'label' => 'Question_desc',
                'rules' => 'required|trim',),
            array(
                'field' => 'question_type',
                'label' => 'Question_type',
                'rules' => 'required|trim',),
            array(
                'field' => 'multiple_votes',
                'label' => 'Multiple_votes',
                'rules' => ''),
            array(
             'field' => 'survey_id',
             'label' => 'Survey_name',
             'rules' => 'required'),
        );

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
						->append_css('module::admin.css');
	}

	/**
	 * List all items
	 */
	public function index()
	{
		$survey = $this->survey_m->order_by('order')->get_all();
			$this->template
		->title($this->module_details['name'])
		->set('survey', $survey)
		->build('admin/index');
	}

	public function create()
	{
		$survey = new StdClass();
		// $folder = $this->file_folders_m->get_by('name', 'survey');
		// $this->data->files = Files::folder_contents($folder->id);
		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// See if the model can create the record
			if($this->survey_m->create($this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('survey:success_create'));
				redirect('admin/survey');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('survey:fail_create'));
				redirect('admin/survey/create');
			}
		}
		$survey->data = new StdClass();
		foreach ($this->item_validation_rules AS $rule)
		{
			$survey->data->{$rule['field']} = $this->input->post($rule['field']);
		}
		$this->_form_data();

        // Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('survey:new_item'))
            ->build('admin/form', $survey->data);

	}

	public function edit($id = 0)
	{
		$this->data = $this->survey_m->get($id);

		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'survey');
		// $this->data->files = Files::folder_contents($folder->id);

		// Set the validation rules from the array above
		$this->form_validation->set_rules($this->item_validation_rules);

		// check if the form validation passed
		if($this->form_validation->run())
		{
			// get rid of the btnAction item that tells us which button was clicked.
			// If we don't unset it MY_Model will try to insert it
			unset($_POST['btnAction']);

			// See if the model can create the record
			if($this->survey_m->edit($id, $this->input->post()))
			{
				// All good...
				$this->session->set_flashdata('success', lang('survey:success_update'));
				redirect('admin/survey');
			}
			// Something went wrong. Show them an error
			else
			{
				$this->session->set_flashdata('error', lang('survey:fail_update'));
				redirect('admin/survey/create');
			}
		}
		// starting point for file uploads
		// $this->data->fileinput = json_decode($this->data->fileinput);
		$this->_form_data();

		// Build the view using sample/views/admin/form.php
		$this->template->title($this->module_details['name'], lang('survey:edit'))
            ->build('admin/form', $this->data);
	}

	public function _form_data()
	{
		// $this->load->model('pages/page_m');
		// $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
	}

	public function delete($id = 0)
	{
        //Hapus Survey Question terlebih dahulu

		// make sure the button was clicked and that there is an array of ids
		if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
		{
			// pass the ids and let MY_Model delete the items
			$this->survey_m->delete_many($this->input->post('action_to'));
		}
		elseif (is_numeric($id))
		{
			// they just clicked the link so we'll delete that one
			$this->survey_m->delete($id);
		}
		redirect('admin/survey');
	}
	public function order() {
		$items = $this->input->post('items');
		$i = 0;
		foreach($items as $item) {
			$item = substr($item, 5);
			$this->survey_m->update($item, array('order' => $i));
			$i++;
		}
	}


    /**
     * List pertanyaan yang ada di dalam suatu survey
     */
    public function list_question($survey_id){
        $survey_question = $this->survey_question_m->where('survey_id',$survey_id)->order_by('order')->get_all();
        if(empty($survey_question)){
            $this->session->set_flashdata('error', lang('survey:invalid_survey'));
            redirect('admin/survey');
        }
        $this->template
            ->title($this->module_details['name'])
            ->set('survey_question', $survey_question)
            ->build('admin/list_question');
    }

    public function create_question(){
        $survey_question = new StdClass();
        // $folder = $this->file_folders_m->get_by('name', 'survey_question');
        // $this->data->files = Files::folder_contents($folder->id);
        // Set the validation rules from the array above
        $this->form_validation->set_rules($this->question_validation_rules);

        // check if the form validation passed
        if($this->form_validation->run())
        {
            $survey_id = $this->input->post('survey_id');
            // See if the model can create the record
            if($this->survey_question_m->create($this->input->post()))
            {
                $question_id = $this->survey_question_m->get_insert_id();

                //Simpan Option
                $this->_save_option($this->input->post('survey_option'), $question_id);

                // All good...
                $this->session->set_flashdata('success', lang('survey_question:success_create'));
                redirect('admin/survey/list_question/'.$survey_id);
            }
            // Something went wrong. Show them an error
            else
            {
                $this->session->set_flashdata('error', lang('survey_question:fail_create'));
                redirect('admin/survey/create_question');
            }
        }
        $survey_question->data = new StdClass();
        foreach ($this->question_validation_rules AS $rule)
        {
            $survey_question->data->{$rule['field']} = $this->input->post($rule['field']);
        }
        $this->_form_data_question();
        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('survey_question.new_item'))
            ->build('admin/form_question', $survey_question->data);
    }

    public function _form_data_question()
    {
        $list_survey = array();
        $surveys = $this->survey_m->order_by('order')->get_all();
        if(!empty($surveys)){
            foreach($surveys as $key=>$survey){
                $list_survey[$survey->id] = $survey->description;
            }
        }
        $this->template->list_survey = $list_survey;
        // $this->load->model('pages/page_m');
        // $this->template->pages = array_for_select($this->page_m->get_page_tree(), 'id', 'title');
    }

    public function edit_question($id = 0)
    {
        $this->data = $this->survey_question_m->get($id);
        $survey_id = $this->data->survey_id;

        // $this->load->model('files/file_folders_m');
        // $folder = $this->file_folders_m->get_by('name', 'survey_question');
        // $this->data->files = Files::folder_contents($folder->id);

        // Set the validation rules from the array above
        $this->form_validation->set_rules($this->question_validation_rules);

        // check if the form validation passed
        if($this->form_validation->run())
        {
            // get rid of the btnAction item that tells us which button was clicked.
            // If we don't unset it MY_Model will try to insert it
            unset($_POST['btnAction']);

            // See if the model can create the record
            if($this->survey_question_m->edit($id, $this->input->post()))
            {
                $question_id = $id;
                //Simpan Option
                $this->_save_option($this->input->post('survey_option'), $question_id);

                // All good...
                $this->session->set_flashdata('success', lang('survey_question:success_update'));
                redirect('admin/survey/list_question/'.$survey_id);
            }
            // Something went wrong. Show them an error
            else
            {
                $this->session->set_flashdata('error', lang('survey_question:fail_update'));
                redirect('admin/survey/create_question');
            }
        }
        // starting point for file uploads
        // $this->data->fileinput = json_decode($this->data->fileinput);
        $this->_form_data_question();

        //Ambil Data Option
        $survey_options = $this->survey_option_m->where('survey_question_id', $id)->order_by('option_desc','ASC')->get_all();
        $this->data->survey_options = $survey_options;

        // Build the view using sample/views/admin/form.php
        $this->template->title($this->module_details['name'], lang('survey_question.edit'))
            ->build('admin/form_question', $this->data);
    }

    public function delete_question($id = 0)
    {
        $this->data = $this->survey_question_m->get($id);
        $survey_id = $this->data->survey_id;

        // make sure the button was clicked and that there is an array of ids
        if (isset($_POST['btnAction']) AND is_array($_POST['action_to']))
        {
            // pass the ids and let MY_Model delete the items
            $this->survey_question_m->delete_many($this->input->post('action_to'));
        }
        elseif (is_numeric($id))
        {
            // they just clicked the link so we'll delete that one
            $this->survey_question_m->delete($id);
        }
        redirect('admin/survey/list_question/'.$survey_id);
    }
    public function order_question() {
        $items = $this->input->post('items');
        $i = 0;
        foreach($items as $item) {
            $item = substr($item, 5);
            $this->survey_question_m->update($item, array('order' => $i));
            $i++;
        }
    }

    private function _save_option($options, $question_id){
        //delete semua option untuk Question tersebut
        $this->survey_option_m->delete_by('survey_question_id', $question_id);

        if(!empty($options)){
            foreach($options as $key=>$option){
                $input = array(
                    'survey_question_id'=>$question_id,
                    'option_value'=>1,
                    'option_desc'=>$option['option_desc'],
                    'weight'=>$option['weight'],
                );
                $this->survey_option_m->create($input);
            }
        }
        return true;
    }

    public function view_result($slug){
        $data = array();
        $data['survey'] = (array)$this->survey_m->get_by_slug($slug);
        $data['survey_result'] = $this->survey_m->get_survey_summary($slug);
        $this->template->title($this->module_details['name'])
            ->append_js('highcharts/highcharts.js')
            ->build('admin/view_result', $data);
    }
}
