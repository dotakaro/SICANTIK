<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Plugin_Segments extends Plugin {
	/**
	 * @author Bakti Aditya
	 * Returns true of segment exists
	 *
	 * usage:
	 * {{ segments:exists names="stupid,parser" }}
	 */
	public function __construct()
	{
		$this->load->helper('dd');
	}

	function exists()
	{
		$modules = explode(',',$this->attribute('names'));
		$segs = $this->uri->segment_array();

		$result = array_intersect($modules, $segs);

		return ($result) ? true : false;
	}
}