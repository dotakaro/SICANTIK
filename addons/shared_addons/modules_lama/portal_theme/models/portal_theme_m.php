<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Modul untuk mengisi data Pegawai
 *
 * @author 		Indra
 * @website		http://indra.com
 * @package 	
 * @subpackage 	
 * @copyright 	MIT
 */
class portal_theme_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'portal_theme';
		 $this->load->model('files/file_folders_m');
		 $this->load->library('files/files');
		 $this->folder = $this->file_folders_m->get_by('name', 'portal_theme');
	}

	//create a new item
	public function create($input)
	{
		$to_insert = array(
            'id' => 1,
            'nama_instansi' => $input['nama_instansi'],
            'warna_dasar' => $input['warna_dasar'],
		);

        if(!empty($_FILES['logo_portal']['name'])){
            $fileinput = Files::upload($this->folder->id, FALSE, 'logo_portal', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['logo_portal'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        if(!empty($_FILES['logo_instansi']['name'])){
            $fileinput = Files::upload($this->folder->id, FALSE, 'logo_instansi', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['logo_instansi'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        if(!empty($_FILES['logo_footer']['name'])){
            $fileinput = Files::upload($this->folder->id, FALSE, 'logo_footer', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['logo_footer'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

		return $this->db->insert('portal_theme', $to_insert);
	}

	//edit a new item
	public function edit($id = 0, $input)
	{
        $this->data = $this->get($id);//Ambil Data sebelumnya

        $to_insert = array(
//            'nama_portal' => $input['nama_portal'],
            'nama_instansi' => $input['nama_instansi'],
            'warna_dasar' => $input['warna_dasar'],
        );

        if(!empty($_FILES['logo_portal']['name'])){
            if(Files::get_file($this->data->logo_portal)){
                Files::delete_file($this->data->logo_portal);
            }

            $fileinput = Files::upload($this->folder->id, FALSE, 'logo_portal', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['logo_portal'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        if(!empty($_FILES['logo_instansi']['name'])){
            if(Files::get_file($this->data->logo_instansi)){
                Files::delete_file($this->data->logo_instansi);
            }

            $fileinput = Files::upload($this->folder->id, FALSE, 'logo_instansi', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['logo_instansi'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        if(!empty($_FILES['logo_footer']['name'])){
            if(Files::get_file($this->data->logo_footer)){
                Files::delete_file($this->data->logo_footer);
            }

            $fileinput = Files::upload($this->folder->id, FALSE, 'logo_footer', false, false, false, 'jpg|jpeg|png|gif');

            if ($fileinput['status']) {
                $to_insert['logo_footer'] = $fileinput['data']['id'];
            }else{
                $this->session->set_flashdata('notice', $fileinput['message']);
                return false;
            }
        }

        return $this->db->where('id', $id)->update('portal_theme', $to_insert);
	}
}
