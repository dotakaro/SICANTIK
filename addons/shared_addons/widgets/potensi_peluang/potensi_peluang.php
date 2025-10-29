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
class Widget_Potensi_peluang extends Widgets
{

	public $author = 'Indra Halim';

	public $website = 'http://www.nukleo.fr';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Potensi Daerah dan Peluang Investasi',
		'br' => 'Artigos recentes do Blog',
            'fa' => 'آخرین ارسال ها',
		'pt' => 'Artigos recentes do Blog',
		'el' => 'Τελευταίες αναρτήσεις ιστολογίου',
		'fr' => 'Derniers articles',
		'ru' => 'Последние записи',
		'id' => 'Potensi Daerah dan Peluang Investasi',
	);

	public $description = array(
		'en' => 'Display Potensi Kabupaten Agam and Peluang Investasi',
		'br' => 'Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog',
            'fa' => 'نمایش آخرین پست های وبلاگ در یک ویجت',
		'pt' => 'Mostra uma lista de navegação para abrir os últimos artigos publicados no Blog',
		'el' => 'Προβάλει τις πιο πρόσφατες αναρτήσεις στο ιστολόγιό σας',
		'fr' => 'Permet d\'afficher la liste des derniers posts du blog dans un Widget',
		'ru' => 'Выводит список последних записей блога внутри виджета',
		'id' => 'Menampilkan Potensi Kabupaten Agam dan Peluang Investasi'
	);

	// build form fields for the backend
	// MUST match the field name declared in the form.php file
	public $fields = array(
		array(
			'field' => 'limit',
			'label' => 'Number of posts',
		)
	);

	public function form($options)
	{
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
		// load the blog module's model
		//class_exists('Blog_m') OR $this->load->model('blog/blog_m');

		// sets default number of posts to be shown
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;
                
		// retrieve the records using the blog module's model
		/*$blog_widget = $this->blog_m
			->limit($options['limit'])
			->get_many_by(array('status' => 'live'));*/		
		$blog_widget = $this->db->query(
                        "SELECT a.* FROM default_blog a 
                            INNER JOIN default_blog_categories b ON a.category_id=b.id 
                            WHERE b.slug IN ('potensi-daerah', 'peluang-investasi') AND a.status='live'
                            ORDER BY a.created DESC
                            LIMIT {$options['limit']}")->result();
		
		// returns the variables to be used within the widget's view
		return array('blog_widget' => $blog_widget);
	}

}
