<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Slideshow Plugin
 *
 * Quick plugin to list slideshow articles
 *
 * @author		Bakti Aditya
 * @copyright	Copyright (c) 2013, BVAP
 */
class Plugin_Slideshow extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Slideshow',
		'id'	=> 'Slideshow',
	);

	public $description = array(
		'en'	=> 'Plugin to display slideshow.',
		'id'	=> 'Plugin untuk menampilkan slideshow.',
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
	 * Slideshow List
	 *
	 * Creates a list of slideshow articles.
	 *
	 * Usage:
	 * {{ slideshow:posts limit="5" }}
	 *		<h2>{{ title }}</h2>
	 * {{ /slideshow:posts }}
	 *
	 * @return	object
	 */
	function posts(){
	    
	    $this->load->model('articles/articles_m');
	    $posts = $this->articles_m->sl_get();
	    foreach ($posts as $key => $post) {
	    	$post->url = site_url('articles/'.date('Y/m', $post->created_on).'/'.$post->slug);
	    }
        return $posts;
    }
}

/* End of file slideshow.php */