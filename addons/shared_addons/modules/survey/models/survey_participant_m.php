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
class survey_participant_m extends MY_Model {

    private $folder;

    public function __construct()
    {
        parent::__construct();
        $this->_table = 'survey_participants';
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
            'nama' 	=> $input['nama'],
            'umur' 	=> $input['umur'],
            'jenis_kelamin' 	=> $input['jenis_kelamin'],
            'pendidikan_terakhir' 	=> $input['pendidikan_terakhir'],
            'pekerjaan_utama' 	=> $input['pekerjaan_utama'],
            'created' 	=> date('Y-m-d H:i:s'),
        );

        return $this->db->insert($this->_table, $to_insert);
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


}
