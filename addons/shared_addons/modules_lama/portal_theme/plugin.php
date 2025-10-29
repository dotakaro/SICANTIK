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
class Plugin_portal_theme extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ portal_theme:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /portal_theme:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('portal_theme/portal_theme_m');
		return $this->portal_theme->get_all();
	}
}

/* End of file plugin.php */