<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Portal Setting Plugin
 *
 * Plugin to get Portal Kominfo Setting
 *
 * @author		Indra Halim
 * @copyright	Copyright (c) 2015, Indra Halim
 */
class Plugin_Portal_setting extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Portal Setting',
		'id'	=> 'Pengaturan Portal',
	);

	public $description = array(
		'en'	=> 'Plugin to display Portal Setting.',
		'id'	=> 'Plugin untuk menampilkan Setting Portal Kominfo.',
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer
	 * to the Blog plugin for a larger example
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			'shrink' => array(
				'description' => array(// a single sentence to explain the purpose of this method
					'en' => 'A Plugin to get Portal Kominfo Setting.'
				),
				'single' => true,// will it work as a single tag?
				'double' => false,// how about as a double tag?
				'variables' => '',// list all variables available inside the double tag. Separate them|like|this
				'attributes' => array(
					'string' => array(// this is the string="Empty" attribute
						'type' => 'text',// Can be: slug, number, flag, text, array, any.
						'flags' => '',// flags are predefined values like asc|desc|random.
						'default' => 'Empty',// this attribute defaults to this if no value is given
						'required' => false,// is this attribute required?
					),
					'limit' => array(// this is the count="155" attribute
						'type' => 'int',// Can be: slug, number, flag, text, array, any.
						'flags' => '',// flags are predefined values like asc|desc|random.
						'default' => '155',// this attribute defaults to this if no value is given
						'required' => false,// is this attribute required?
					),
					'delimiter' => array(// this is the count="155" attribute
						'type' => 'text',// Can be: slug, number, flag, text, array, any.
						'flags' => '',// flags are predefined values like asc|desc|random.
						'default' => '...',// this attribute defaults to this if no value is given
						'required' => false,// is this attribute required?
					),
				),
			),
		);

		return $info;
	}

	/**
	 * Generate
	 *
	 * Usage:
	 * {{ excerpt:get_setting field="warna_dasar"}}
	 *
	 * @return string
	 */

    function get_setting()
    {
        $setting_value = '';
        $field = $this->attribute('field', 'nama_instansi');
//        $existingThemeSession = $this->session->userdata('theme_session');
//        if(is_null($existingThemeSession)){//Jika belum ada session, buat dan simpan setting ke session
            $CI = &get_instance();
            $CI->load->model('portal_theme/portal_theme_m');
            $portal_theme_m = new portal_theme_m();
            $id = 1;//sesuai record di database
            $data = $portal_theme_m->get($id);
            if(!empty($data) && isset($data->$field)){
                $themeSession = array('theme_session'=>$data);
            }
            $this->session->set_userdata($themeSession);
            $setting_value = $data->$field;
//        }else{
//            $setting_value = $existingThemeSession->$field;
//        }
        return $setting_value;
    }
}

/* End of file excerpt.php */