<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Discussion extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Discussion'
				),
			'description' => array(
				'en' => 'Modul untuk Diskusi di Backend'
				),
			'frontend' => false,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Discussion',
			'sections' => array(
				'items' => array(
					'name' 	=> 'discussion:items', // These are translated from your language file
					'uri' 	=> 'admin/discussion',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'discussion:create',
							'uri' 	=> 'admin/discussion/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('discussion');
        $this->dbforge->drop_table('discussion_comment');
		//$this->db->delete('settings', array('module' => 'discussion'));

		// $this->load->library('files/files');
		// Files::create_folder(0, 'discussion');

		$discussion = array(
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
            'topic' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
            ),
            'message_to' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'created' => array(
                'type' => 'datetime',
                'null'=>true
            ),
        );

        $discussion_comment = array(
            'id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'auto_increment' => TRUE
            ),
            'discussion_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true
            ),
            'comment' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
            ),
            'created_by' => array(
                'type' => 'INT',
                'constraint' => '11',
            ),
            'created' => array(
                'type' => 'datetime',
                'null'=>true
            ),
        );

		// $discussion_setting = array(
		// 	'slug' => 'discussion_setting',
		// 	'title' => 'Discussion Setting',
		// 	'description' => 'A Yes or No option for the Discussion module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'discussion'
		// 	);

		$this->dbforge->add_field($discussion);
		$this->dbforge->add_key('id', TRUE);
        $create_discussion = $this->dbforge->create_table('discussion');

        $this->dbforge->add_field($discussion_comment);
        $this->dbforge->add_key('id', TRUE);
        $create_discussion_comment = $this->dbforge->create_table('discussion_comment');


        if(
            $create_discussion AND
            $create_discussion_comment
            //AND
		   //$this->db->insert('settings', $discussion_setting) AND
			//is_dir($this->upload_path.'discussion') OR @mkdir($this->upload_path.'discussion',0777,TRUE)
        )
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		 //$this->load->library('files/files');
		 ///$this->load->model('files/file_folders_m');
		 //$folder = $this->file_folders_m->get_by('name', 'discussion');
		// Files::delete_folder($folder->id);
		$this->dbforge->drop_table('discussion');
        $this->dbforge->drop_table('discussion_comment');
		//$this->db->delete('settings', array('module' => 'discussion'));
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
