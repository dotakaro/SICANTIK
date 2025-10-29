<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Survey extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Survey'
				),
			'description' => array(
				'en' => 'Modul untuk Create Survey'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Survey',
			'sections' => array(
				'items' => array(
					'name' 	=> 'survey:survey', // These are translated from your language file
					'uri' 	=> 'admin/survey',
					'shortcuts' => array(
						'create' => array(
                                'name' 	=> 'survey:create_survey',
                                'uri' 	=> 'admin/survey/create',
                                'class' => 'add'
							),
                            array(
                                'name' 	=> 'survey:create_question',
                                   'uri' 	=> 'admin/survey/create_question',
                                'class' => 'add'
                            )
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('survey');
		$this->dbforge->drop_table('survey_question');
		//$this->db->delete('settings', array('module' => 'survey'));

		// $this->load->library('files/files');
		// Files::create_folder(0, 'survey');

		$survey = array(
			'id' => array(
				'type' => 'INT',
				'constraint' => '11',
				'auto_increment' => TRUE
				),
			'order' => array(
				'type' => 'INT',
				'constraint' => '11',
				'null' => true
				),
			'slug' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'description' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'open_date' => array(
                'type' => 'DATE',
                'null' =>true
            ),
            'close_date' => array(
                'type' => 'DATE',
                'null' =>true
            ),
            'active' => array(
                'type' => 'INT',
            ),
        );

        $survey_question = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'order' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ),
            'question_desc' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'question_type' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'multiple_votes' => array(
                'type' => 'INT',
                'constraint' => '1',
                'default' => '0',
            ),
            'survey_id' =>array(
                'type' => 'INT',
                'constraint'=>11
            )
        );

        $survey_option = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'order' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ),
            'option_value' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'option_desc' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'weight' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'survey_question_id' =>array(
                'type' => 'INT',
                'constraint' => '11'
            )
        );

        $survey_answers = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'order' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ),
            'answer' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'survey_question_id' =>array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'survey_voter_id' =>array(
                'type' => 'INT',
                'constraint' => '11'
            )
        );

        $survey_voters = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'order' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ),
            'ip_address' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
            ),
            'session_id' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
            ),
            'user_id' => array(
                'type'=>'INT',
                'constraint'=>11,
                'null' => true
            ),
            'survey_id' =>array(
                'type' => 'INT',
                'constraint' => '11'
            ),
            'timestamp'=>array(
                'type'=>'INT',
                'constraint'=>11
            )
        );

        $survey_participants = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'order' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ),
            'nama' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
            ),
            'umur' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'jenis_kelamin' => array(
                'type'=>'VARCHAR',
                'constraint'=>50,
            ),
            'pendidikan_terakhir' =>array(
                'type' => 'VARCHAR',
                'constraint' => '100'
            ),
            'pekerjaan_utama'=>array(
                'type'=>'VARCHAR',
                'constraint'=> '100'
            ),
            'created'=>array(
                'type'=>'DATETIME'
            )
        );

        // $survey_setting = array(
		// 	'slug' => 'survey_setting',
		// 	'title' => 'Survey Setting',
		// 	'description' => 'A Yes or No option for the Survey module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'survey'
		// 	);

		$this->dbforge->add_field($survey);
		$this->dbforge->add_key('id', TRUE);
        $create_table_survey = $this->dbforge->create_table('surveys');

		$this->dbforge->add_field($survey_question);
		$this->dbforge->add_key('id', TRUE);
        $create_table_question = $this->dbforge->create_table('survey_questions');

		$this->dbforge->add_field($survey_option);
		$this->dbforge->add_key('id', TRUE);
        $create_table_option = $this->dbforge->create_table('survey_options');

        $this->dbforge->add_field($survey_answers);
        $this->dbforge->add_key('id', TRUE);
        $create_table_answer = $this->dbforge->create_table('survey_answers');

        $this->dbforge->add_field($survey_voters);
        $this->dbforge->add_key('id', TRUE);
        $create_table_voter = $this->dbforge->create_table('survey_voters');

        $this->dbforge->add_field($survey_participants);
        $this->dbforge->add_key('id', TRUE);
        $create_table_participant = $this->dbforge->create_table('survey_participants');

        if(
            (
                $create_table_survey && $create_table_question && $create_table_option &&
                $create_table_answer && $create_table_voter && $create_table_participant
            )
            &&
		   //$this->db->insert('settings', $survey_setting) AND
			is_dir($this->upload_path.'survey') || @mkdir($this->upload_path.'survey',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'survey');
		// Files::delete_folder($folder->id);
		$this->dbforge->drop_table('surveys');
        $this->dbforge->drop_table('survey_questions');
        $this->dbforge->drop_table('survey_options');
        $this->dbforge->drop_table('survey_answers');
        $this->dbforge->drop_table('survey_voters');
        $this->dbforge->drop_table('survey_participants');
		//$this->db->delete('settings', array('module' => 'survey'));
		{
			return TRUE;
		}
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return TRUE;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
