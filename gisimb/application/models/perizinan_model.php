<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_model extends CI_Model
{
    private $_table = "tbl_perizinan";

    public $pendaftaran_id;
    public $nama;
    public $desa;
    public $kecamtan;
    public $kota;
    public $koordinat_long;
    public $koordinat_lat;



    public function rules()
    {
        return [
            ['field' => 'pendafataran_id',
            'label' => 'Nomor Pendafataran',
            'rules' => 'required'],

            ['field' => 'nama',
            'label' => 'Nama Pemilik',
            'rules' => 'required'],
            
            ['field' => 'koordinat_long',
            'label' => 'Kord. Longitude',
            'rules' => 'required'],

            ['field'=> 'koordinat_lat',
            'label' => 'Kord. Latitude',
            'rules' => 'required']

        ];
    }

    public function getAll()
    {
        return $this->db->get($this->_table)->result();
    }
    
    public function getById($id)
    {
        return $this->db->get_where($this->_table, ["pendaftaran_id" => $id])->row();
    }

    public function save()
    {
        $post = $this->input->post();
        $this->pendaftaran_id = $post['pendaftaran_id'];
        $this->nama = $post["name"];
        $this->desa = $post["desa"];
        $this->kecanatan = $post["kecanatan"];
        $this->kota = $post["kota"];
        $this->koordinat_long = $post["koordinat_long"];
        $this->koordinat_lat = $post["koordinat_lat"];
       
        $this->db->insert($this->_table, $this);
    }

    public function update()
    {
        $post = $this->input->post();
        $this->pendaftaran_id = $post['pendaftaran_id'];
        $this->nama = $post["name"];
        $this->desa = $post["desa"];
        $this->kecanatan = $post["kecanatan"];
        $this->kota = $post["kota"];
        $this->koordinat_long = $post["koordinat_long"];
        $this->koordinat_lat = $post["koordinat_lat"];
       
       
    
		
		// if (!empty($_FILES["image"]["name"])) {
        //     $this->image = $this->_uploadImage();
        // } else {
        //     $this->image = $post["old_image"];
		// }

        $this->db->update($this->_table, $this, array('pendaftaran_id' => $post['id']));
    }

    public function delete($id)
    {
		//$this->_deleteImage($id);
        return $this->db->delete($this->_table, array("pendaftaran_id" => $id));
	}
	
	private function _uploadImage()
	{
		$config['upload_path']          = './upload/product/';
		$config['allowed_types']        = 'gif|jpg|png';
		$config['file_name']            = $this->product_id;
		$config['overwrite']			= true;
		$config['max_size']             = 1024; // 1MB
		// $config['max_width']            = 1024;
		// $config['max_height']           = 768;

		$this->load->library('upload', $config);

		if ($this->upload->do_upload('image')) {
			return $this->upload->data("file_name");
		}
		
		return "default.jpg";
	}

	private function _deleteImage($id)
	{
		$product = $this->getById($id);
		if ($product->image != "default.jpg") {
			$filename = explode(".", $product->image)[0];
			return array_map('unlink', glob(FCPATH."upload/product/$filename.*"));
		}
	}

}
