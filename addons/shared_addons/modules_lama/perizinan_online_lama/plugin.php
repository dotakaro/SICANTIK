<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Perizinan Online
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_perizinan_online extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ perizinan_online:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /perizinan_online:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('perizinan_online/perizinan_online_m');
		return $this->perizinan_online_m->get_all();
	}
}

/* End of file plugin.php */