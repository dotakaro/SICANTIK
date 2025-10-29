<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Link_website extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Link Website'
				),
			'description' => array(
				'en' => 'Modul untuk Mengisi Link Website'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Link Website',
			'sections' => array(
				'items' => array(
					'name' 	=> 'link_website:items', // These are translated from your language file
					'uri' 	=> 'admin/link_website',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'link_website:create',
							'uri' 	=> 'admin/link_website/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('link_website');
		//$this->db->delete('settings', array('module' => 'link_website'));

		// $this->load->library('files/files');
		// Files::create_folder(0, 'link_website');

		$link_website = array(
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
			'nama_link' => array(
				'type' => 'VARCHAR',
	'constraint' => '100',
),
'url_link' => array(
				'type' => 'VARCHAR',
	'constraint' => '200',
),
'desc_link' => array(
				'type' => 'VARCHAR',
	'constraint' => '500',
),

			);

		// $link_website_setting = array(
		// 	'slug' => 'link_website_setting',
		// 	'title' => 'Link Website Setting',
		// 	'description' => 'A Yes or No option for the Link Website module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'link_website'
		// 	);

		$this->dbforge->add_field($link_website);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('link_website') AND
		   //$this->db->insert('settings', $link_website_setting) AND
			is_dir($this->upload_path.'link_website') OR @mkdir($this->upload_path.'link_website',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'link_website');
		// Files::delete_folder($folder->id);
		$this->dbforge->drop_table('link_website');
		//$this->db->delete('settings', array('module' => 'link_website'));
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
