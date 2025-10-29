<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Mengisi Link Website
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_link_website extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ link_website:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /link_website:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('link_website/link_website_m');
		return $this->link_website_m->get_all();
	}
}

/* End of file plugin.php */