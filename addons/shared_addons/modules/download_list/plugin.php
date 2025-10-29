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
class Plugin_download_list extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ download_list:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /download_list:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('download_list/download_list_m');
		return $this->download_list_m->get_all();
	}
}

/* End of file plugin.php */