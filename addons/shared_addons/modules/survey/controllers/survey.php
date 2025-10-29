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
class survey extends Public_Controller
{

    /**
     * The constructor
     * @access public
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->lang->load('survey');
        $this->lang->load('survey_question');

        $this->load->model('survey_m');
        $this->load->model('survey_question_m');
        $this->load->model('survey_option_m');
        $this->load->model('survey_answer_m');
        $this->load->model('survey_voter_m');
        $this->load->model('survey_participant_m');

        $this->template->append_css('module::survey.css');
    }
     /**
     * List all surveys
     *
     *
     * @access  public
     * @return  void
     */
     public function index()
     {
      // bind the information to a key
      $data['survey'] = (array)$this->survey_m->get_all();
      // Build the page
      $this->template->title($this->module_details['name'])
      ->build('index', $data);
    }

    public function participate($survey_slug){
        // bind the information to a key
        $survey_questions = array();
        $data['survey'] = (array)$this->survey_m->get_detail_by_slug($survey_slug);
        $data['already_voted'] = $this->survey_voter_m->already_voted($data['survey']['id']);
        // Build the page
        $this->template->title($this->module_details['name'])
            ->build('participate', $data);
    }

    /**
     * Fungsi untuk menyimpan hasil survey yang telah diisi
     */
    public function save_result(){
        $answers = $this->input->post('answer');
        $participantData = $this->input->post('participant');
        $surveyData = $this->input->post('survey');
        if(!empty($answers)){
            //TODO Tambahkan pengecekan apakah seseorang sudah pernah ikut survey atau belum

            if($this->survey_participant_m->create($participantData)){
                $this->survey_voter_m->insert_voter($surveyData['id']);
                $survey_voter_id = $this->survey_voter_m->get_insert_id();
                foreach($answers as $key=>$answer){
                    $answer['survey_voter_id'] = $survey_voter_id;
                    $this->survey_answer_m->create($answer);
                }
                $this->session->set_flashdata('success', lang('survey:survey_done'));
            }else{
                $this->session->set_flashdata('error', lang('survey:survey_failed'));
            }
        }else{
            $this->session->set_flashdata('error', lang('survey:survey_failed'));
        }
        $survey = $this->survey_m->get($surveyData['id']);
        redirect('survey/survey_done/'.$survey->slug);
    }

    /**
     *
     */
    public function survey_done($slug){
        $data = array();
        $data['survey'] = (array)$this->survey_m->get_by_slug($slug);
        $this->template->title($this->module_details['name'])
            ->build('survey_done', $data);
    }

    public function view_results($slug){
        $data = array();
        $data['survey'] = (array)$this->survey_m->get_by_slug($slug);
        $data['survey_result'] = $this->survey_m->get_survey_summary($slug);
        $this->template->title($this->module_details['name'])
            ->build('view_results', $data);
    }

}

/* End of file survey.php */