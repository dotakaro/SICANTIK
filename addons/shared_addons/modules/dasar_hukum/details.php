<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Dasar_hukum extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Dasar Hukum'
				),
			'description' => array(
				'en' => 'Modul untuk memanage Dasar Hukum'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Download List',
			'sections' => array(
				'items' => array(
					'name' 	=> 'dasar_hukum:items', // These are translated from your language file
					'uri' 	=> 'admin/dasar_hukum',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'dasar_hukum:create',
							'uri' 	=> 'admin/dasar_hukum/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('dasar_hukum');
		//$this->db->delete('settings', array('module' => 'dasar_hukum'));

		 $this->load->library('files/files');
		 Files::create_folder(0, 'dasar_hukum');

		$dasar_hukum = array(
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
			'pdf_dasar_hukum' => array(
				'type' => 'VARCHAR',
                'constraint' => '100',
                'null' => true
            ),
            'nama_dasar_hukum' => array(
                            'type' => 'VARCHAR',
                'constraint' => '250',
            ),
            'published' => array(
                            'type' => 'INT',
                'constraint' => '1',
            ),
            'created' => array(
                            'type' => 'datetime',
                            'null'=>true
                            ),
            'updated' => array(
                            'type' => 'datetime',
                            'null'=>true
            ),

			);

		// $dasar_hukum_setting = array(
		// 	'slug' => 'dasar_hukum_setting',
		// 	'title' => 'Download List Setting',
		// 	'description' => 'A Yes or No option for the Download List module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'dasar_hukum'
		// 	);

		$this->dbforge->add_field($dasar_hukum);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('dasar_hukum') AND
		   //$this->db->insert('settings', $dasar_hukum_setting) AND
			is_dir($this->upload_path.'dasar_hukum') OR @mkdir($this->upload_path.'dasar_hukum',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'dasar_hukum');
		 Files::delete_folder($folder->id);
		$this->dbforge->drop_table('dasar_hukum');
		//$this->db->delete('settings', array('module' => 'dasar_hukum'));
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
