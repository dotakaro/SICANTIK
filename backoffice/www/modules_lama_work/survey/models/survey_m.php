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
class survey_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'surveys';
		// $this->load->model('files/file_folders_m');
		// $this->load->library('files/files');
		// $this->folder = $this->file_folders_m->get_by('name', 'survey');
	}

	//create a new item
	public function create($input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			// 'fileinput' => json_encode($fileinput);
			'slug' => $input['slug'],
	        'description' => $input['description'],
            'open_date' => $input['open_date'],
            'close_date' => $input['close_date'],
            'active' => (isset($input['active']))?1:0,
		);

		return $this->db->insert('surveys', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'slug' => $input['slug'],
            'description' => $input['description'],
            'open_date' => $input['open_date'],
            'close_date' => $input['close_date'],
            'active' => (isset($input['active']))?1:0,
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('surveys', $to_insert);
	}

    public function retrieve_surveys($only_active = true, $limit=5){
        if($only_active){
            $this->db->where('active',1);
        }
        $surveys = $this->db->limit($limit)->get('surveys')->result_array();
        return $surveys;
    }

    public function retrieve_survey($survey_id){
        $this->db->where('id',$survey_id);
        $surveys = $this->db->get('surveys')->result_array();
        if(!empty($surveys)){
            $surveys = $surveys[0];
        }
        return $surveys;
    }


    /**
     * Fungsi untuk mendapatkan Data Survey beserta Pertanyaan dan Optionnnya
     * @param $survey_slug
     * @return array
     */
    public function get_detail_by_slug($survey_slug){
        $ret = array();
        $survey_questions = array();
        $CI = get_instance();
        $CI->load->model('survey_question_m');

        $this->db->where('slug', $survey_slug);
        $survey = $this->db->get('surveys')->result_array();
        if(!empty($survey)){
            $ret = $survey[0];
            $questions = $this->survey_question_m->where('survey_id',$ret['id'])->order_by('order')->get_all();
            if(!empty($questions)){
                foreach($questions as $key=>$question){
                    $survey_questions[$key]['id'] = $question->id;
                    $survey_questions[$key]['question_desc'] = $question->question_desc;
                    $survey_questions[$key]['question_type'] = $question->question_type;
                    $survey_questions[$key]['multiple_votes'] = $question->multiple_votes;
                    if($question->question_type=='option'){
                        $options = $this->survey_option_m->where('survey_question_id',$question->id)->order_by('order')->get_all();
                        if(!empty($options)){
                            $survey_questions[$key]['options'] = $this->objectToArray($options);
                        }
                    }
                }
            }
            $ret['questions'] = $survey_questions;
        }
        return $ret;
    }

    /**
     * Fungsi untuk mendapatkan Data Survey beserta Pertanyaan dan Optionnnya
     * @param $survey_slug
     * @return array
     */
    public function get_by_slug($survey_slug){
        $ret = array();
        $this->db->where('slug', $survey_slug);
        $survey = $this->db->get('surveys')->result_array();
        if(!empty($survey)){
            $ret = $survey[0];
        }
        return $ret;
    }

    public function objectToArray($d) {
        $ret = array();
        if(!empty($d) && (is_array($d) || is_object($d))){
            foreach($d as $key=>$value){
                if (!empty($value)) {
                    $childParse = self::objectToArray($value);
                    if(is_array($childParse) && !empty($childParse)){
                        $value = $childParse;
                    }
                }else{
                    if(is_object($value)){
                        $value = (array) $value;
                    }
                }
                $ret[$key] = $value;
            }
        }
        return $ret;
    }

    public function get_survey_summary($slug){
        $surveyResults = array();
        $sqlHeader = "
            SELECT
                s.*
            FROM default_surveys s
            WHERE
                s.slug= '{$slug}'
        ";
        $queryHeader = $this->db->query($sqlHeader);
        $resultHeader = $queryHeader->result();

        if(!empty($resultHeader)){
            $surveyResults['description'] = $resultHeader[0]->description;
            $surveyResults['open_date'] = $resultHeader[0]->open_date;
            $surveyResults['close_date'] = $resultHeader[0]->close_date;

            $sql = "
                SELECT
                    sq.id, sq.question_desc, so.weight, sa.answer,
                    COUNT(sa.answer)AS num_votes
                FROM default_surveys s
                    INNER JOIN default_survey_questions sq ON sq.survey_id = s.id
                    INNER JOIN default_survey_answers sa ON sa.survey_question_id = sq.id
                    INNER JOIN default_survey_options so ON so.option_desc=sa.answer AND so.survey_question_id = sa.survey_question_id
                WHERE
                    s.slug= '{$slug}'
                    AND sq.question_type = 'option'
                GROUP BY sq.question_desc, sa.answer, so.weight
            ";
            $query = $this->db->query($sql);
            $results = $query->result();

            if(!empty($results)){
                foreach($results as $result){
                    $surveyResults['questions'][$result->id]['question'] = $result->question_desc;
                    $surveyResults['questions'][$result->id]['summary'][] = array(
                        'answer'=>$result->answer,
                        'weight'=>(int)$result->weight,
                        'num_votes'=>(int)$result->num_votes,
                    );
                }
            }
        }
        return $surveyResults;
    }
}
