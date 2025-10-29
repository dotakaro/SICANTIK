<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk memanage Dasar Hukum
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class dasar_hukum_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'dasar_hukum';
		 $this->load->model('files/file_folders_m');
		 $this->load->library('files/files');
		 $this->folder = $this->file_folders_m->get_by('name', 'dasar_hukum');
	}

	//create a new item
	public function create($input)
	{
        $to_insert = array(
            'nama_dasar_hukum' => $input['nama_dasar_hukum'],
            'published' => $input['published'],
            'created'=>date('Y-m-d H:i:s')
        );
        if(!empty($_FILES['pdf_dasar_hukum']['name'])){
		    $fileinput = Files::upload($this->folder->id, FALSE, 'pdf_dasar_hukum');
            if ($fileinput['status']) {
                $to_insert['pdf_dasar_hukum'] = $fileinput['data']['id'];
             }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

		return $this->db->insert('dasar_hukum', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
		$to_insert = array(
            'nama_dasar_hukum' => $input['nama_dasar_hukum'],
            'published' => $input['published'],
            'updated'=>date('Y-m-d H:i:s')
		);

        if(!empty($_FILES['pdf_dasar_hukum']['name'])){
            $fileinput = Files::upload($this->folder->id, FALSE, 'pdf_dasar_hukum');
            if ($fileinput['status']) {
                $to_insert['pdf_dasar_hukum'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

		return $this->db->where('id', $id)->update('dasar_hukum', $to_insert);
	}
}
