<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Create Survey
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	com.indra.survey
 * @subpackage 	
 * @copyright 	MIT
 */
class Plugin_survey extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 *
	 * {{ survey:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /survey:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$this->load->model('survey/survey_m');
		return $this->survey_m->get_all();
	}
}

/* End of file plugin.php */