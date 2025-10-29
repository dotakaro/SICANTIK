<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Info Singkat
 *
 * @author 		Info Singkat
 * @website		
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_info_singkat extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ info_singkat:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /info_singkat:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('info_singkat/info_singkat_m');
		return $this->info_singkat_m->get_all();
	}
}

/* End of file plugin.php */