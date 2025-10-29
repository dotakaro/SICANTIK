<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Perizinan Online
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class perizinan_online_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'perizinan_online';
		// $this->load->model('files/file_folders_m');
		// $this->load->library('files/files');
		// $this->folder = $this->file_folders_m->get_by('name', 'perizinan_online');
	}

	//create a new item
	public function create($input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			// 'fileinput' => json_encode($fileinput);
			'id_pemohon' => $input['id_pemohon'],
                        'jenis_identitas' => $input['jenis_identitas'],
                        'nama_pemohon' => $input['nama_pemohon'],
                        'telp_pemohon' => $input['telp_pemohon'],
                        'alamat_pemohon' => $input['alamat_pemohon'],
                        'provinsi_pemohon' => $input['provinsi_pemohon'],
                        'kabupaten_pemohon' => $input['kabupaten_pemohon'],
                        'kecamatan_pemohon' => $input['kecamatan_pemohon'],
                        'kelurahan_pemohon' => $input['kelurahan_pemohon'],
                        'npwp_perusahaan' => $input['npwp_perusahaan'],
                        'no_register_perusahaan' => $input['no_register_perusahaan'],
                        'nama_perusahaan' => $input['nama_perusahaan'],
                        'alamat_perusahaan' => $input['alamat_perusahaan'],
                        'telepon_perusahaan' => $input['telepon_perusahaan'],
                        'provinsi_perusahaan' => $input['provinsi_perusahaan'],
                        'kabupaten_perusahaan' => $input['kabupaten_perusahaan'],
                        'kecamatan_perusahaan' => $input['kecamatan_perusahaan'],
                        'kelurahan_perusahaan' => $input['kelurahan_perusahaan'],
                        'lampiran' => $input['lampiran'],
                        'jenis_izin' => $input['jenis_izin'],
                        'urut' => $input['urut'],
                        'nama_perizinan' => $input['nama_perizinan'],
                        'no_pendaftaran' => $input['no_pendaftaran'],
                        'provinsi_pemohon_text' => $input['provinsi_pemohon_text'],
                        'kabupaten_pemohon_text' => $input['kabupaten_pemohon_text'],
                        'kecamatan_pemohon_text' => $input['kecamatan_pemohon_text'],
                        'kelurahan_pemohon_text' => $input['kelurahan_pemohon_text'],
                        'provinsi_perusahaan_text' => $input['provinsi_perusahaan_text'],
                        'kabupaten_perusahaan_text' => $input['kabupaten_perusahaan_text'],
                        'kecamatan_perusahaan_text' => $input['kecamatan_perusahaan_text'],
                        'kelurahan_perusahaan_text' => $input['kelurahan_perusahaan_text'],
                        'unit_kerja_id' => $input['unit_kerja_id'],
                        'unit_kerja_text' => $input['unit_kerja_text'],
		);

		return $this->db->insert('perizinan_online', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		// $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
		$to_insert = array(
			'id_pemohon' => $input['id_pemohon'],
                        'jenis_identitas' => $input['jenis_identitas'],
                        'nama_pemohon' => $input['nama_pemohon'],
                        'telp_pemohon' => $input['telp_pemohon'],
                        'alamat_pemohon' => $input['alamat_pemohon'],
                        'provinsi_pemohon' => $input['provinsi_pemohon'],
                        'kabupaten_pemohon' => $input['kabupaten_pemohon'],
                        'kecamatan_pemohon' => $input['kecamatan_pemohon'],
                        'kelurahan_pemohon' => $input['kelurahan_pemohon'],
                        'npwp_perusahaan' => $input['npwp_perusahaan'],
                        'no_register_perusahaan' => $input['no_register_perusahaan'],
                        'nama_perusahaan' => $input['nama_perusahaan'],
                        'alamat_perusahaan' => $input['alamat_perusahaan'],
                        'telepon_perusahaan' => $input['telepon_perusahaan'],
                        'provinsi_perusahaan' => $input['provinsi_perusahaan'],
                        'kabupaten_perusahaan' => $input['kabupaten_perusahaan'],
                        'kecamatan_perusahaan' => $input['kecamatan_perusahaan'],
                        'kelurahan_perusahaan' => $input['kelurahan_perusahaan'],
                        'lampiran' => $input['lampiran'],
                        'jenis_izin' => $input['jenis_izin'],
                        'urut' => $input['urut'],
                        'nama_perizinan' => $input['nama_perizinan'],
                        'no_pendaftaran' => $input['no_pendaftaran'],
                        'provinsi_pemohon_text' => $input['provinsi_pemohon_text'],
                        'kabupaten_pemohon_text' => $input['kabupaten_pemohon_text'],
                        'kecamatan_pemohon_text' => $input['kecamatan_pemohon_text'],
                        'kelurahan_pemohon_text' => $input['kelurahan_pemohon_text'],
                        'provinsi_perusahaan_text' => $input['provinsi_perusahaan_text'],
                        'kabupaten_perusahaan_text' => $input['kabupaten_perusahaan_text'],
                        'kecamatan_perusahaan_text' => $input['kecamatan_perusahaan_text'],
                        'kelurahan_perusahaan_text' => $input['kelurahan_perusahaan_text'],
                        'unit_kerja_id' => $input['unit_kerja_id'],
                        'unit_kerja_text' => $input['unit_kerja_text'],
		);

		// if ($fileinput['status']) {
		// 	$to_insert['fileinput'] = json_encode($fileinput);
		// }

		return $this->db->where('id', $id)->update('perizinan_online', $to_insert);
	}

    /**
     * Fungsi untuk mendapatkan list izin yang sedang dalam proses cetak di backoffice
     * @param $offset
     * @param $limit
     * @return array
     */
    function get_all_proses($limit, $offset){
        $ret = array();
        $num_rows = 0;

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
                $num_rows = $this->xml_parsing_win->value_in('num_rows', $item);

                $item_array[] = array(
                    'no_pendaftaran' => $no_pendaftaran,
                    'nama_pemohon' => $nama_pemohon,
                    'nama_perizinan' => $nama_perizinan
                );
            }
        }
        $ret['rows'] = $item_array;
        $ret['num_rows'] = $num_rows;

        return $ret;
    }

    /**
     * Fungsi untuk mendapatkan list izin yang sudah diterbitkan di backoffice
     * @param $offset
     * @param $limit
     * @return array
     */
    function get_all_proses_terbit($limit, $offset){
        $ret = array();
        $num_rows = 0;

        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');

        //Cari ke backoffice untuk
        $url = $this->curl->simple_get("$base_url_websevices/api/listpermohonanterbit/limit//$limit/offset//$offset");
        $news_items = $this->xml_parsing_win->element_set('item', $url);

        if ($news_items == NULL) {
            $item_array = array();
        } else {
            foreach ($news_items as $item) {
                $no_pendaftaran = $this->xml_parsing_win->value_in('pendaftaran_id', $item);
                $nama_pemohon = $this->xml_parsing_win->value_in('n_pemohon', $item);
                $nama_perizinan = $this->xml_parsing_win->value_in('n_perizinan', $item);
                $num_rows = $this->xml_parsing_win->value_in('num_rows', $item);
 				$no_surat = $this->xml_parsing_win->value_in('no_surat', $item);

                $item_array[] = array(
                    'no_pendaftaran' => $no_pendaftaran,
                    'nama_pemohon' => $nama_pemohon,
                    'nama_perizinan' => $nama_perizinan,
					'no_surat' => $no_surat
                );
            }
        }
        $ret['rows'] = $item_array;
        $ret['num_rows'] = $num_rows;

        return $ret;
    }

    /**
     * Fungsi untuk mendapatkan list izin yang sudah selesai namun belum diambil di backoffice
     * @param $offset
     * @param $limit
     * @return array
     */
    function get_all_proses_ambil($limit, $offset){
        $ret = array();
        $num_rows = 0;

        $this->load->library('curl');
        $this->load->library('xml_parsing_win');

        $base_url_websevices = Settings::get('perizinan_online_webservice');

        //Cari ke backoffice untuk
        $url = $this->curl->simple_get("$base_url_websevices/api/listpermohonanambil/limit//$limit/offset//$offset");
        $news_items = $this->xml_parsing_win->element_set('item', $url);

        if ($news_items == NULL) {
            $item_array = array();
        } else {
            foreach ($news_items as $item) {
                $no_pendaftaran = $this->xml_parsing_win->value_in('pendaftaran_id', $item);
                $nama_pemohon = $this->xml_parsing_win->value_in('n_pemohon', $item);
                $nama_perizinan = $this->xml_parsing_win->value_in('n_perizinan', $item);
                $num_rows = $this->xml_parsing_win->value_in('num_rows', $item);

                $item_array[] = array(
                    'no_pendaftaran' => $no_pendaftaran,
                    'nama_pemohon' => $nama_pemohon,
                    'nama_perizinan' => $nama_perizinan
                );
            }
        }
        $ret['rows'] = $item_array;
        $ret['num_rows'] = $num_rows;

        return $ret;
    }
}
