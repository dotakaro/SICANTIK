<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk Pengaduan Online
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class pengaduan_online_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'pengaduan_online';
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
                    'nama' => $input['nama'],
                    'alamat' => $input['alamat'],
                    'provinsi' => $input['provinsi'],
                    'provinsi_text' => $input['provinsi_text'],
                    'kabupaten' => $input['kabupaten'],
                    'kabupaten_text' => $input['kabupaten_text'],
                    'kecamatan' => $input['kecamatan'],
                    'kecamatan_text' => $input['kecamatan_text'],
                    'kelurahan' => $input['kelurahan'],
                    'kelurahan_text' => $input['kelurahan_text'],
                    'deskripsi_pengaduan' =>$input['deskripsi_pengaduan'],
//                    'urut' => $input['urut'],
                    'tanggal'=>date('Y-m-d')
		);

		return $this->db->insert('pengaduan_online', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
            // $fileinput = Files::upload($this->folder->id, FALSE, 'fileinput');
            $to_insert = array(
                // 'fileinput' => json_encode($fileinput);
                'nama' => $input['nama'],
                'alamat' => $input['alamat'],
                'provinsi' => $input['provinsi'],
                'provinsi_text' => $input['provinsi_text'],
                'kabupaten' => $input['kabupaten'],
                'kabupaten_text' => $input['kabupaten_text'],
                'kecamatan' => $input['kecamatan'],
                'kecamatan_text' => $input['kecamatan_text'],
                'kelurahan' => $input['kelurahan'],
                'kelurahan_text' => $input['kelurahan_text'],
                'deskripsi_pengaduan' =>$input['deskripsi_pengaduan'],
                'urut' => $input['urut']
            );

            // if ($fileinput['status']) {
            // 	$to_insert['fileinput'] = json_encode($fileinput);
            // }

            return $this->db->where('id', $id)->update('pengaduan_online', $to_insert);
	}
}
