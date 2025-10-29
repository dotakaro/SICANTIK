<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Diskusi di Backend
 *
 * @author 		Indra Halm
 * @website		http://indra.com
 * @package 	com.indra.pyro.discussion
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_discussion extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ discussion:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /discussion:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('discussion/discussion_m');
		return $this->discussion_m->get_all();
	}
}

/* End of file plugin.php */