<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Perizinan_online extends Module
{

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Perizinan Online'
            ),
            'description' => array(
                'en' => 'Modul untuk Perizinan Online'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Perizinan Online',
            'sections' => array(
                'items' => array(
                    'name' => 'perizinan_online:items', // These are translated from your language file
                    'uri' => 'admin/perizinan_online',
//					'shortcuts' => array(
//						'create' => array(
//							'name' 	=> 'perizinan_online:create',
//							'uri' 	=> 'admin/perizinan_online/create',
//							'class' => 'add'
//							)
//						)
                )
            )
        );
    }

    public function install()
    {
        $this->dbforge->drop_table('perizinan_online');
        $this->db->delete('settings', array('module' => 'perizinan_online'));

        // $this->load->library('files/files');
        // Files::create_folder(0, 'perizinan_online');

        $perizinan_online = array(
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
            'id_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'jenis_identitas' => array(
                'type' => 'VARCHAR',
                'constraint' => '10',
            ),
            'nama_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
            ),
            'telp_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
            ),
            'alamat_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
            ),
            'provinsi_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'kabupaten_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'kecamatan_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'kelurahan_pemohon' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'npwp_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'no_register_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'nama_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'alamat_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
                'null' => true,
            ),
            'telepon_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '25',
                'null' => true,
            ),
            'provinsi_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'kabupaten_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'kecamatan_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'kelurahan_perusahaan' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'lampiran' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'jenis_izin' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
            ),
            'urut' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
            ),
            'nama_perizinan' => array(
                'type' => 'VARCHAR',
                'constraint' => '150',
                'null' => true,
            ),
            'no_pendaftaran' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
            ),
            'provinsi_pemohon_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'kabupaten_pemohon_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'kecamatan_pemohon_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'kelurahan_pemohon_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'provinsi_perusahaan_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'kabupaten_perusahaan_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'kecamatan_perusahaan_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'kelurahan_perusahaan_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
            'unit_kerja_id' => array(
                'type' => 'INT',
                'constraint' => '11',
                'null' => true,
            ),
            'unit_kerja_text' => array(
                'type' => 'VARCHAR',
                'constraint' => '200',
                'null' => true,
            ),
        );

        $perizinan_online_setting = array(
            'slug' => 'perizinan_online_webservice',
            'title' => 'Perizinan Online Setting',
            'description' => 'URL Web Service untuk Perizinan Online',
            '`default`' => '',
            '`value`' => '',
            'type' => 'text',
            '`options`' => '',
            'is_required' => 1,
            'is_gui' => 1,
            'module' => 'perizinan_online'
        );

        $this->dbforge->add_field($perizinan_online);
        $this->dbforge->add_key('id', TRUE);

        if ($this->dbforge->create_table('perizinan_online') AND
            $this->db->insert('settings', $perizinan_online_setting) AND
            is_dir($this->upload_path . 'perizinan_online') OR @mkdir($this->upload_path . 'perizinan_online', 0777, TRUE)
        ) {
            return TRUE;
        }
    }

    public function uninstall()
    {
        // $this->load->library('files/files');
        // $this->load->model('files/file_folders_m');
        // $folder = $this->file_folders_m->get_by('name', 'perizinan_online');
        // Files::delete_folder($folder->id);
        $this->dbforge->drop_table('perizinan_online');
        $this->db->delete('settings', array('module' => 'perizinan_online'));
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
