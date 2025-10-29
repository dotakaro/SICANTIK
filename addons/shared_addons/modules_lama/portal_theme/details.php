<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @author Indra Halim
 * @created 2015-08-29
 * Modul ini digunakan di Theme Portal Kominfo
 * Class Module_Portal_theme
 */
class Module_Portal_theme extends Module
{

    public $version = '1.0';

    public function info()
    {
        return array(
            'name' => array(
                'en' => 'Portal Theme'
            ),
            'description' => array(
                'en' => 'Modul untuk mengatur tampilan Portal'
            ),
            'frontend' => true,
            'backend' => true,
            'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Pegawai',
            /*'sections' => array(
                'items' => array(
                    'name' => 'portal_theme:items', // These are translated from your language file
                    'uri' => 'admin/portal_theme',
                    'shortcuts' => array(
                        'create' => array(
                            'name' => 'portal_theme:create',
                            'uri' => 'admin/portal_theme/create',
                            'class' => 'add'
                        )
                    )
                )
            )*/
        );
    }

    public function install()
    {
//        $this->dbforge->drop_table('portal_theme');
        //$this->db->delete('settings', array('module' => 'portal_theme'));

        $this->load->library('files/files');
        Files::create_folder(0, 'portal_theme');

        $portal_theme_table = array(
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
            /*'nama_portal' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
            ),*/
            'nama_instansi' => array(
                'type' => 'VARCHAR',
                'constraint' => '500',
            ),
            'warna_dasar' => array(
                'type' => 'VARCHAR',
                'constraint' => '100',
            ),
            'logo_portal' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ),
            'logo_instansi' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ),
            'logo_footer' => array(
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true
            ),
        );

        // $portal_theme_setting = array(
        // 	'slug' => 'portal_theme_setting',
        // 	'title' => 'Pegawai Setting',
        // 	'description' => 'A Yes or No option for the Pegawai module',
        // 	'`default`' => '1',
        // 	'`value`' => '1',
        // 	'type' => 'select',
        // 	'`options`' => '1=Yes|0=No',
        // 	'is_required' => 1,
        // 	'is_gui' => 1,
        // 	'module' => 'portal_theme'
        // 	);

        $this->dbforge->add_field($portal_theme_table);
        $this->dbforge->add_key('id', TRUE);

//        if ($this->dbforge->create_table('portal_theme') AND
        if(
            //$this->db->insert('settings', $portal_theme_setting) AND
            is_dir($this->upload_path . 'portal_theme') OR @mkdir($this->upload_path . 'portal_theme', 0777, TRUE)
        ) {
            return TRUE;
        }
    }

    public function uninstall()
    {
        $this->load->library('files/files');
        $this->load->model('files/file_folders_m');
        $folder = $this->file_folders_m->get_by('name', 'portal_theme');
        Files::delete_folder($folder->id);
//        $this->dbforge->drop_table('portal_theme');
        //$this->db->delete('settings', array('module' => 'portal_theme'));
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
