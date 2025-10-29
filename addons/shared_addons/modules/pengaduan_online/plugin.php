<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Pengaduan Online
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_pengaduan_online extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ pengaduan_online:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /pengaduan_online:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('pengaduan_online/pengaduan_online_m');
		return $this->pengaduan_online_m->get_all();
	}
}

/* End of file plugin.php */