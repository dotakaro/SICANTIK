<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show Latest article in your site with a widget.
 *
 * Intended for use on cms pages. Usage :
 * on a CMS page add:
 *
 *     {widget_area('name_of_area')}
 *
 * 'name_of_area' is the name of the widget area you created in the  admin
 * control panel
 *
 * @author  Erik Berman
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Articles\Widgets
 */
class Widget_Tracking_perizinan extends Widgets
{

	public $author = 'Indra Halim';

	public $website = 'http://www.indra.com';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Tracking Status Perizinan',
		'id' => 'Tracking Status Perizinan',
	);

	public $description = array(
		'en' => 'Tracking Status Perizinan',
		'id' => 'Tracking Status Perizinan',
	);

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'backoffice_tracking_perizinan',
			'label' => 'URL Backoffice Tracking Perizinan',
		)
	);

	public function form($options)
	{
		//$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		return $options;
	}

}
