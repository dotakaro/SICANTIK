<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Widget untuk menampilkan daftar permohonan yang sedang diproses
 * (semua daftar pemohon dikurangi daftar izin yang sudah proses cetak izin pada back office)
 * Intended for use on cms pages. Usage :
 * on a CMS page add:
 *
 *     {widget_area('name_of_area')}
 *
 * 'name_of_area' is the name of the widget area you created in the  admin
 * control panel
 *
 * @author  Indra Halim
 * @author  Batra Dev Team
 * @package PyroCMS\Core\Modules\Articles\Widgets
 */
class Widget_Permohonan_proses extends Widgets
{

	public $author = 'Indra Halim';

	public $website = 'http://www.indra.com';

	public $version = '1.0.0';

	public $title = array(
		'en' => 'Daftar Permohonan',
		'id' => 'Daftar Permohonan',
	);

	public $description = array(
		'en' => 'Daftar Permohonan yang sedang diproses',
		'id' => 'Daftar Permohonan yang sedang diproses',
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
		$options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		return array(
			'options' => $options
		);
	}

	public function run($options)
	{
        // sets default number of posts to be shown
        $options['limit'] = ( ! empty($options['limit'])) ? $options['limit'] : 5;

		$daftar_permohonan = $this->_get_list_permohonan($options['limit']);

        // returns the variables to be used within the widget's view
        return array('daftar_permohonan' => $daftar_permohonan);
	}

    private function _get_list_permohonan($limit = 5){
        $offset = 0;
        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');

        //Cari ke backoffice untuk
        $url = $this->curl->simple_get("$base_url_websevices/api/listpermohonanproses/limit//$limit/offset//$offset");
        $news_items = $this->xml_parsing_win->element_set('item', $url);

        if ($news_items == NULL) {
            $item_array = array();
        } else {
            foreach ($news_items as $item) {
                $no_pendaftaran = $this->xml_parsing_win->value_in('pendaftaran_id', $item);
                $nama_pemohon = $this->xml_parsing_win->value_in('n_pemohon', $item);
                $nama_perizinan = $this->xml_parsing_win->value_in('n_perizinan', $item);

                $item_array[] = array(
                    'no_pendaftaran' => $no_pendaftaran,
                    'nama_pemohon' => $nama_pemohon,
                    'nama_perizinan' => $nama_perizinan
                );
            }
        }
        return $item_array;
    }

}
