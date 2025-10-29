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
class survey_voter_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'survey_voters';
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
            'session_id' 	=> $this->session->userdata('session_id'),
            'ip_address' 	=> $this->session->userdata('ip_address'),
            'survey_id' => $input['survey_id']
		);

		return $this->db->insert('survey_voters', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
            'session_id' 	=> $this->session->userdata('session_id'),
            'ip_address' 	=> $this->session->userdata('ip_address'),
            'survey_id' => $input['survey_id']
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('survey_voters', $to_insert);
	}

    public function get_insert_id(){
        return $this->db->insert_id();
    }

    /**
     * Record user details in database
     *
     * Make sure the same user does not vote multiple times in the same poll (unless we allow it!)
     *
     * @access public
     * @param int			Survey ID
     * @return void
     */
    public function insert_voter($survey_id)
    {
        $data = array(
            'survey_id' 		=> $survey_id,
            'user_id' 		=> $this->ion_auth->logged_in() ? $this->session->userdata('user_id') : NULL,
            'session_id' 	=> $this->session->userdata('session_id'),
            'ip_address' 	=> $this->session->userdata('ip_address'),
            'timestamp' 	=> time()
        );

        $this->db->insert('survey_voters', $data);

        return $this->db->affected_rows() > 0 ? TRUE : FALSE;
    }

    /**
     * Has current user already voted in this survey?
     *
     * @access public
     * @param int			Survey ID
     * @return null
     */
    public function already_voted($survey_id)
    {
        // IP address are considered unique for one week
        $expire = 604800;
        $now = time();

        // First, let's see if we can find this poll in the userdata
        if ( $this->session->userdata('survey_' . $survey_id) )
        {
            return TRUE;
        }

        $user_id = $this->ion_auth->logged_in() ? $this->session->userdata('user_id') : NULL;
        $session_id = $this->session->userdata('session_id');;
        $ip_address = $this->session->userdata('ip_address');

        /*
         * Get all poll voters that have voted in a particular poll
         *
         * Where the user ID is the same as the current user -OR-
         * The session ID is the same as the current user -OR-
         * The IP address is the same as the current user (but assume that an IP is unique to a user for only one week)
         */
        $query = $this->db->query("
			SELECT *
			FROM "  . $this->db->dbprefix('survey_voters') . "
			WHERE survey_id = $survey_id AND
				(
					user_id = '$user_id' OR
					session_id = '$session_id' OR
					(ip_address = '$ip_address' AND timestamp + $expire < $now)
				)
		");

        return $query->num_rows() > 0 ? TRUE : FALSE;
    }

}
