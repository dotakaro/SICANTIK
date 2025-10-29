<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Gallery extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Gallery'
				),
			'description' => array(
				'en' => 'Modul untuk Upload Gallery'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Gallery',
			'sections' => array(
				'items' => array(
					'name' 	=> 'gallery:items', // These are translated from your language file
					'uri' 	=> 'admin/gallery',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'gallery:create',
							'uri' 	=> 'admin/gallery/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('gallery');
		//$this->db->delete('settings', array('module' => 'gallery'));

		 $this->load->library('files/files');
		 Files::create_folder(0, 'gallery');

		$gallery = array(
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
			'gallery_file' => array(
				'type' => 'VARCHAR',
	'constraint' => '200',
),
'gallery_desc' => array(
				'type' => 'VARCHAR',
	'constraint' => '500',
),
'published' => array(
				'type' => 'INT',
	'constraint' => '1',
),
                    'created'=>array(
                        'type'=>'datetime',
                        'null'=>true
                    ),
                    'updated'=>array(
                        'type'=>'datetime',
                        'null'=>true
                    )
			);

		// $gallery_setting = array(
		// 	'slug' => 'gallery_setting',
		// 	'title' => 'Gallery Setting',
		// 	'description' => 'A Yes or No option for the Gallery module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'gallery'
		// 	);

		$this->dbforge->add_field($gallery);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('gallery') AND
		   //$this->db->insert('settings', $gallery_setting) AND
			is_dir($this->upload_path.'gallery') OR @mkdir($this->upload_path.'gallery',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'gallery');
		 Files::delete_folder($folder->id);
		$this->dbforge->drop_table('gallery');
		//$this->db->delete('settings', array('module' => 'gallery'));
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
