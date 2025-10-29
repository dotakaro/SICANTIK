<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk mengisi data Pegawai
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_pegawai extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ pegawai:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /pegawai:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('pegawai/pegawai_m');
		return $this->pegawai_m->get_all();
	}
}

/* End of file plugin.php */