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
class Plugin_daftar_layanan extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ daftar_layanan:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /daftar_layanan:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('daftar_layanan/daftar_layanan_m');
		return $this->daftar_layanan_m->get_all();
	}
}

/* End of file plugin.php */