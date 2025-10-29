<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Info_singkat extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Info Singkat'
				),
			'description' => array(
				'en' => 'Modul untuk Info Singkat'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Info Singkat',
			'sections' => array(
				'items' => array(
					'name' 	=> 'info_singkat:items', // These are translated from your language file
					'uri' 	=> 'admin/info_singkat',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'info_singkat:create',
							'uri' 	=> 'admin/info_singkat/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('info_singkat');
		//$this->db->delete('settings', array('module' => 'info_singkat'));

		// $this->load->library('files/files');
		// Files::create_folder(0, 'info_singkat');

		$info_singkat = array(
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
			'isi_info' => array(
				'type' => 'VARCHAR',
	'constraint' => '200',
),
'published' => array(
				'type' => 'INT',
	'constraint' => '1',
	'default' => '1',
),
'created'=>array(
	'type'=>'datetime',
	'null'=>true
),
'modified'=>array(
	'type'=>'datetime',
	'null'=>true
)
			);

		// $info_singkat_setting = array(
		// 	'slug' => 'info_singkat_setting',
		// 	'title' => 'Info Singkat Setting',
		// 	'description' => 'A Yes or No option for the Info Singkat module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'info_singkat'
		// 	);

		$this->dbforge->add_field($info_singkat);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('info_singkat') AND
		   //$this->db->insert('settings', $info_singkat_setting) AND
			is_dir($this->upload_path.'info_singkat') OR @mkdir($this->upload_path.'info_singkat',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'info_singkat');
		// Files::delete_folder($folder->id);
		$this->dbforge->drop_table('info_singkat');
		//$this->db->delete('settings', array('module' => 'info_singkat'));
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
