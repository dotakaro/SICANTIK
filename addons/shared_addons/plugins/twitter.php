<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Twitter Plugin
 *
 * Quick plugin to add twitter
 *
 * @author		Bakti Aditya
 * @copyright	Copyright (c) 2013, BVAP
 */
class Plugin_Twitter extends Plugin
{
	public $version = '1.0.0';

	public $name = array(
		'en'	=> 'Twitter',
		'id'	=> 'Twitter',
	);

	public $description = array(
		'en'	=> 'Plugin to display twitter.',
		'id'	=> 'Plugin untuk menampilkan twitter.',
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
	 * Twitter
	 *
	 * Creates a button of twitter share.
	 *
	 * Usage:
	 *
	 * @return	string
	 */

	public function button()
	{
		$url    = urlencode($this->attribute('url'));
		$text   = urlencode($this->attribute('text', ''));
		$placeholder   = urlencode($this->attribute('placeholder', 'Tweet'));
		$tweets = self::get_tweets($url);
		//$button = sprintf('<a target="_blank" data-count="%d" title="Share on Twitter" href="http://twitter.com/share?text=%s&url=%s" class="btn btn-counter" rel="nofollow">%s</a>​​​​​', $tweets, $text, $url, $placeholder);
		$button = '<div class="tw-btn-o"><a href="http://twitter.com/share?text='.$text.'&url='.$url.'" class="tw-btn" target="_blank"><i></i><span class="tw-label">Tweet</span></a></div><div class="tw-count-o"><i></i><u></u><a id="tw-count" title="This page has been shared '.$tweets.' times. View these Tweets." aria-describedby="count-desc">'.$tweets.'</a></div>';
		return $button;
	}

	private static function get_tweets($url)
	{
		$api = "http://urls.api.twitter.com/1/urls/count.json?url=";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_URL, $api.$url);
		$result = json_decode(curl_exec($ch));

		return $result->count;
	}
}

/* End of file slideshow.php */