<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk memanage File-file yang dapat didownload oleh Visitor Website
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_dasar_hukum extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ dasar_hukum:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /dasar_hukum:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('dasar_hukum/dasar_hukum_m');
		return $this->dasar_hukum_m->get_all();
	}
}

/* End of file plugin.php */