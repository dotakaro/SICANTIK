<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Download_list extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Download List'
				),
			'description' => array(
				'en' => 'Modul untuk memanage File-file yang dapat didownload oleh Visitor Website'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Download List',
			'sections' => array(
				'items' => array(
					'name' 	=> 'download_list:items', // These are translated from your language file
					'uri' 	=> 'admin/download_list',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'download_list:create',
							'uri' 	=> 'admin/download_list/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('download_list');
		//$this->db->delete('settings', array('module' => 'download_list'));

		 $this->load->library('files/files');
		 Files::create_folder(0, 'download_list');

		$download_list = array(
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
			'file_download' => array(
				'type' => 'VARCHAR',
	'constraint' => '100',
),
'file_desc' => array(
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

		// $download_list_setting = array(
		// 	'slug' => 'download_list_setting',
		// 	'title' => 'Download List Setting',
		// 	'description' => 'A Yes or No option for the Download List module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'download_list'
		// 	);

		$this->dbforge->add_field($download_list);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('download_list') AND
		   //$this->db->insert('settings', $download_list_setting) AND
			is_dir($this->upload_path.'download_list') OR @mkdir($this->upload_path.'download_list',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'download_list');
		 Files::delete_folder($folder->id);
		$this->dbforge->drop_table('download_list');
		//$this->db->delete('settings', array('module' => 'download_list'));
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
