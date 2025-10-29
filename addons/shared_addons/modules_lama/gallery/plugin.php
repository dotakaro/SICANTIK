<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Upload Gallery
 *
 * @author 		Indra Halim
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_gallery extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ gallery:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /gallery:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('gallery/gallery_m');
		return $this->gallery_m->get_all();
	}
}

/* End of file plugin.php */