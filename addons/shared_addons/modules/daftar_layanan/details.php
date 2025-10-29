<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Daftar_layanan extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Daftar Layanan'
				),
			'description' => array(
				'en' => 'Modul untuk memanage File-file yang dapat didownload oleh Visitor Website'
				),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Download List',
			'sections' => array(
				'items' => array(
					'name' 	=> 'daftar_layanan:items', // These are translated from your language file
					'uri' 	=> 'admin/daftar_layanan',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'daftar_layanan:create',
							'uri' 	=> 'admin/daftar_layanan/create',
							'class' => 'add'
							)
						)
					)
				)
			);
	}

	public function install()
	{
		$this->dbforge->drop_table('daftar_layanan');
		//$this->db->delete('settings', array('module' => 'daftar_layanan'));

		 $this->load->library('files/files');
		 Files::create_folder(0, 'daftar_layanan');

		$daftar_layanan = array(
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
            'jenis_file' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
			'file_desc' => array(
				'type' => 'VARCHAR',
				'constraint' => '250',
			),
			'jenis_izin'=>array(
				'type'=>'INT',
				'constraint'=>'11'
			),
			'nama_perizinan'=>array(
				'type'=>'VARCHAR',
				'constraint'=>'250'
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
			)
		);

		 $daftar_layanan_setting = array(
		 	'slug' => 'daftar_layanan_setting',
		 	'title' => 'Daftar Layanan Setting',
		 	'description' => 'URL Web Service untuk Daftar Layanan',
                        'options'=>'',
		 	'default`' => '',
		 	'value`' => '',
            'slug'=>'daftar_layanan_webservice',
		 	'type' => 'text',
		 	'is_required' => 1,
		 	'is_gui' => 1,
		 	'module' => 'daftar_layanan'
		);

		$this->dbforge->add_field($daftar_layanan);
		$this->dbforge->add_key('id', TRUE);

		if($this->dbforge->create_table('daftar_layanan') AND
		   $this->db->insert('settings', $daftar_layanan_setting) AND
			is_dir($this->upload_path.'daftar_layanan') OR @mkdir($this->upload_path.'daftar_layanan',0777,TRUE))
		{
			return TRUE;
		}
	}

	public function uninstall()
	{
		 $this->load->library('files/files');
		 $this->load->model('files/file_folders_m');
		 $folder = $this->file_folders_m->get_by('name', 'daftar_layanan');
		 Files::delete_folder($folder->id);
		$this->dbforge->drop_table('daftar_layanan');
		$this->db->delete('settings', array('module' => 'daftar_layanan'));
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
