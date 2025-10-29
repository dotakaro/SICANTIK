<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Show Latest blog in your site with a widget.
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
 * @package PyroCMS\Core\Modules\Blog\Widgets
 */
class Widget_Minimalistic_slideshow extends Widgets
{

	public $author = 'Indra Halim';

	public $website = 'http://www.indra.com';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Minimalistic Slideshow Widget',
		'br' => 'Artigos recentes do Blog',
            'fa' => 'آخرین ارسال ها',
		'pt' => 'Artigos recentes do Blog',
		'el' => 'Τελευταίες αναρτήσεις ιστολογίου',
		'fr' => 'Derniers articles',
		'ru' => 'Последние записи',
		'id' => 'Minimalistic Slideshow Widget',
	);

	public $description = array(
		'en' => 'Widget untuk menampilkan gambar dengan Minimalisitic Slideshow',
		'br' => 'Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog',
            'fa' => 'نمایش آخرین پست های وبلاگ در یک ویجت',
		'pt' => 'Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog',
		'el' => 'Προβάλει τις πιο πρόσφατες αναρτήσεις στο ιστολόγιό σας',
		'fr' => 'Permet d\'afficher la liste des derniers posts du blog dans un Widget',
		'ru' => 'Выводит список последних записей блога внутри виджета',
		'id' => 'Widget untuk menampilkan gambar dengan Minimalisitic Slideshow',
	);

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of links',
		),
        array(
            'field'=>'folder_id',
            'label'=>'Image Folder ID'
        ),
	);

	public function form($options)
	{
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;
        $options['folder_id'] = $options['folder_id'];
		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		// load the blog module's model
		
		// sets default number of posts to be shown
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;
        $options['folder_id'] = $options['folder_id'];

		// returns the variables to be used within the widget's view
		return array('options' => $options);
	}

}
