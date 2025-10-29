<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Excerpt Plugin
 *
 * Quick plugin to generate excerpt for some string
 *
 * @author		Bakti Aditya
 * @copyright	Copyright (c) 2013, BVAP
 */
class Plugin_Excerpt extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Excerpt',
		'id'	=> 'Ringkasan',
	);

	public $description = array(
		'en'	=> 'Plugin to display excerpt from a text.',
		'id'	=> 'Plugin untuk menampilkan kutipan dari teks.',
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
					'en' => 'A simple excerpt generator.'
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
	 * {{ excerpt:generate string="some text" limit="155" delimiter=".." }}
	 *
	 * @return string
	 */

	function generate(){
	    $string = $this->attribute('string', 'Empty');
	    $limit = $this->attribute('limit', 155);
	    $delimiter = $this->attribute('delimiter', '...');

	    /*
        $words = explode(' ', $string);
        if (count($words) > $limit)
        {
            $words = array_slice($words, 0, $limit);
            $string = implode(' ', $words) . $delimiter;
        }
        */

        if (strlen($string) > $limit) {
			$string = preg_replace('/<[^>]*>/', ' ', $string);
			$string = substr($string, 0, $limit);
			$string = substr($string, 0, strrpos($string," "));
			$string = $string . $delimiter;
		}

        return $string;
    }
}

/* End of file excerpt.php */