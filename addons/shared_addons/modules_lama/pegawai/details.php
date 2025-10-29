<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Pegawai extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Pegawai'
				),
			'description' => array(
				'en' => 'Modul untuk mengisi data Pegawai'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Pegawai',
			'sections' => array(
				'items' => array(
					'name' 	=> 'pegawai:items', // These are translated from your language file
					'uri' 	=> 'admin/pegawai',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'pegawai:create',
							'uri' 	=> 'admin/pegawai/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('pegawai');
		//$this->db->delete('settings', array('module' => 'pegawai'));

		 $this->load->library('files/files');
		 Files::create_folder(0, 'pegawai');

		$pegawai = array(
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
			'nama_pegawai' => array(
				'type' => 'VARCHAR',
	'constraint' => '200',
),
'nip' => array(
				'type' => 'VARCHAR',
	'constraint' => '25',
),
'jabatan' => array(
				'type' => 'VARCHAR',
	'constraint' => '100',
),
'alamat' => array(
				'type' => 'VARCHAR',
	'constraint' => '500',
),
'tempat_lahir' => array(
				'type' => 'VARCHAR',
	'constraint' => '50',
),
'tgl_lahir' => array(
				'type' => 'DATE',
),
'no_telp' => array(
				'type' => 'VARCHAR',
	'constraint' => '20',
),
'pendidikan' => array(
				'type' => 'VARCHAR',
	'constraint' => '255',
),
'foto' => array(
				'type' => 'VARCHAR',
	'constraint' => '50',
),

			);

		// $pegawai_setting = array(
		// 	'slug' => 'pegawai_setting',
		// 	'title' => 'Pegawai Setting',
		// 	'description' => 'A Yes or No option for the Pegawai module',
		// 	'`default`' => '1',
		// 	'`value`' => '1',
		// 	'type' => 'select',
		// 	'`options`' => '1=Yes|0=No',
		// 	'is_required' => 1,
		// 	'is_gui' => 1,
		// 	'module' => 'pegawai'
		// 	);

		$this->dbforge->add_field($pegawai);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('pegawai') AND
		   //$this->db->insert('settings', $pegawai_setting) AND
			is_dir($this->upload_path.'pegawai') OR @mkdir($this->upload_path.'pegawai',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'pegawai');
		 Files::delete_folder($folder->id);
		$this->dbforge->drop_table('pegawai');
		//$this->db->delete('settings', array('module' => 'pegawai'));
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
