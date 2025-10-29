<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Keywords Plugin
 *
 * Quick plugin to generate keywords for some modules
 *
 * @author		Bakti Aditya
 * @copyright	Copyright (c) 2013, BVAP
 */
class Plugin_Keywords extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Keywords',
		'id'	=> 'Keywords',
	);

	public $description = array(
		'en'	=> 'Plugin to display all keywords.',
		'id'	=> 'Plugin to display all keywords.',
	);

	/**
	 * Returns a PluginDoc array that PyroCMS uses 
	 * to build the reference in the admin panel
	 *
	 * All options are listed here but refer 
	 * to the Blog plugin for a larger example
	 *
	 * @return array
	 */
	public function _self_doc()
	{
		$info = array(
			
		);
	
		return $info;
	}

	/**
	 * Get
	 *
	 * Usage:
	 * {{ keywords:get limit="20" }}
	 *
	 * @documentation http://docs.pyrocms.com/2.1/manual/developers/tools/keywords
	 * @return string
	 */
	
	function get(){
		/* $limit = $this->attribute('limit', 30);
		$result = array();

		// Get keywords from all articles
		$articles = $this->get_all();
		

		foreach ($articles as $key => $article) {
			if ( $article->keywords ) {
				$keywords = Keywords::get_string($article->keywords);
				$pieces = explode(", ", $keywords);
				for ($i=0; $i < count($pieces); $i++) { 
					array_push($result, array(
						'name' => $pieces[$i],
						'url' => base_url().'articles/tagged/'.rawurlencode($pieces[$i]),
						)
					);
				}
			}
		}

		// Remove duplicate array
		$a = array_map("unserialize", array_unique(array_map("serialize", $result)));

		// Randomize array, call function on line 101
		$b = $this->array_random_assoc($a);

		$total = count($b);

		if ($total < $limit) {
			$b[$total-1] = $b[$total-1] + array('last' => true);
		} else {
			$b[$limit-1] = $b[$limit-1] + array('last' => true);
		}

		//echo '<pre>', print_r($b, true), '</pre>'; die();
        return array_slice($b, 0, $limit); */
		
		$limit = $this->attribute('limit', 30);
		
		$this->load->library(array('keywords/keywords'));

		$posts = $this->db->select('keywords')->get('articles')->result();

		$buffer = array(); // stores already added keywords
		$tags   = array();

		foreach($posts as $p)
		{
			$kw = Keywords::get_array($p->keywords);

			foreach($kw as $k)
			{
				$k = trim(strtolower($k));

				if(!in_array($k, $buffer)) // let's force a unique list
				{
					$buffer[] = $k;

					$tags[] = array(
						'name' => ucfirst($k),
						'url'   => site_url('articles/tagged/'.$k)
					);
				}
			}
		}
		
		$count_tags = count($tags);
		$tags = $this->array_random_assoc($tags);	
		
		if($count_tags > $limit) // Enforce the limit
		{
			$tags = array_slice($tags, 0, $limit);
			$count_tags = $limit;
		}
			
		if($count_tags > 1) $tags[$count_tags - 1]['last'] = true;
		
		return $tags;
    }

    private function get_all()
	{
		$this->db
			->where('status', 'live')
			->order_by('id', 'DESC');

		return $this->db->get('articles')->result();
	}

	private function array_random_assoc($list) {
		if (!is_array($list)) return $list; 

		$keys = array_keys($list); 
		shuffle($keys); 
		$random = array();

		for ($i=0; $i< count($list); $i++) {
			$key = $keys[$i];
			$random[$i] = $list[$key];
		}

		return $random;
	}
}

/* End of file keywords.php */