<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Pengaduan_online extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Pengaduan Online'
				),
			'description' => array(
				'en' => 'Modul untuk Pengaduan Online'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Perizinan Online',
			'sections' => array(
				'items' => array(
					'name' 	=> 'pengaduan_online:items', // These are translated from your language file
					'uri' 	=> 'admin/pengaduan_online'
				)
			)
                );
	}

	public function install()
	{
		$this->dbforge->drop_table('pengaduan_online');
		$this->db->delete('settings', array('module' => 'pengaduan_online'));

		// $this->load->library('files/files');
		// Files::create_folder(0, 'perizinan_online');

		$pengaduan_online = array(
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
                                'constraint' => '200',
                        ),
                        'alamat' => array(
                                                        'type' => 'VARCHAR',
                                'constraint' => '500',
                        ),
                        'provinsi' => array(
                                                        'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                        ),
                        'provinsi_text' => array(
                                                        'type' => 'VARCHAR',
                                'constraint' => '150',
                                'null' => true,
                        ),
                        'kabupaten' => array(
                                                        'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                        ),
                        'kabupaten_text' => array(
                                                        'type' => 'VARCHAR',
                                'constraint' => '150',
                                'null' => true,
                        ),
                        'kecamatan' => array(
                                                        'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                        ),
                        'kecamatan_text' => array(
                                                        'type' => 'VARCHAR',
                                'constraint' => '150',
                                'null' => true,
                        ),
                        'kelurahan' => array(
                                                        'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                        ),
                        'kelurahan_text' => array(
                                                        'type' => 'VARCHAR',
                                'constraint' => '150',
                                'null' => true,
                        ),
                        'deskripsi_pengaduan' => array(
                                                        'type' => 'VARCHAR',
                                'constraint' => '500',
                                'null' => true,
                        ),
                        'urut' => array(
                                                        'type' => 'INT',
                                'constraint' => '11',
                                'null' => true,
                        ),
                        'tanggal' => array(
                                'type' => 'date',
                                'constraint' => '0',
                                'null' => true,
                        ),
		);

		 $pengaduan_online_setting = array(
		 	'slug' => 'pengaduan_online_setting',
		 	'title' => 'Pengaduan Online Setting',
		 	'description' => 'URL Web Service untuk Pengaduan Online',
                        'options'=>'',
		 	'default`' => '',
		 	'value`' => '',
                        'slug'=>'pengaduan_online_webservice',
		 	'type' => 'text',
		 	'is_required' => 1,
		 	'is_gui' => 1,
		 	'module' => 'pengaduan_online'
		 	);

		$this->dbforge->add_field($pengaduan_online);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('pengaduan_online') AND
		$this->db->insert('settings', $pengaduan_online_setting) AND
			is_dir($this->upload_path.'pengaduan_online') OR @mkdir($this->upload_path.'pengaduan_online',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		// $this->load->library('files/files');
		// $this->load->model('files/file_folders_m');
		// $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
		// Files::delete_folder($folder->id);
		$this->dbforge->drop_table('pengaduan_online');
		$this->db->delete('settings', array('module' => 'pengaduan_online'));
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
