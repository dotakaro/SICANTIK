<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * function to truncate text (into a preview or excerpt) with trailing dots.
 *
 * @author Bakti Aditya
 *
 */
if ( ! function_exists('excerpt'))
{
	function excerpt($string = '', $limit = 124, $delimiter = '..')
	{
		if (strlen($string) > $limit) {
			$string = preg_replace('/<[^>]*>/', ' ', $string);
			$string = substr($string, 0, $limit);
			$string = substr($string, 0, strrpos($string," "));
			$string = $string . $delimiter;
		}
		return $string;
	}
}