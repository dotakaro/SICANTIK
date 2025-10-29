<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * function to display current array or object properties
 *
 * @author Bakti Aditya
 *
 */
if ( ! function_exists('dd'))
{
	function dd($string = '', $die = true)
	{
		echo '<pre>', print_r($string, true), '</pre>';
        if ($die) die();
	}
}