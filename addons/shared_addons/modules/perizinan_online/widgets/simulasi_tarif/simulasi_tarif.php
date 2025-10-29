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
 * @author  Indra Halim
 * @author  PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Articles\Widgets
 */
class Widget_Simulasi_tarif extends Widgets
{

	public $author = 'Indra Halim';

	public $website = 'http://www.indra.com';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Simulasi Tarif',
		'id' => 'Simulasi Tarif',
	);

	public $description = array(
		'en' => 'Widget Simulasi Tarif',
		'id' => 'Widget Simulasi Tarif',
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
        ### Ambil data dari webservice CURL Backoffice##
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');

        //List Perizinan
        $url = $this->curl->simple_get("$base_url_websevices/api/jenisperizinantariflist");
        $news_items = $this->xml_parsing_win->element_set('item', $url);
        $item_array = array();
        foreach ($news_items as $item) {
            $id = $this->xml_parsing_win->value_in('id', $item);
            $jenis = $this->xml_parsing_win->value_in('jenis_perizinan', $item);
            $v_hari = $this->xml_parsing_win->value_in('v_hari', $item);

            $item_array[] = array(
                'id' => $id,
                'jenis_perizinan' => $jenis,
                'v_hari' => $v_hari
            );
        }

        $options['list_izin_simulasi'] =  $item_array;
        ##############################################

        return $options;
	}

}
