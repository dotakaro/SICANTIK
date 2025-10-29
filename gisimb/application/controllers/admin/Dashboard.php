<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Dashboard extends CI_Controller {
    public function __construct()
    {
		parent::__construct();
		$this->load->library('leaflet');
		$this->load->model("user_model");
		if($this->user_model->isNotLogin()) redirect(site_url('admin/login'));
	}

	public function index()
	{
			$config = array(
            'center'         => '3.111965,98.4734686', // Center of the map
			'zoom'           => 14, // Map zoom
			'click'			 => 'onMapClick',
			);
			$this->leaflet->initialize($config);
			

        
        	$marker = array(
            'latlng' 		=>'3.106845, 98.499448', // Marker Location
            'popupContent' 	=> 'Aloha PupUP !! COY !!', // Popup Content
            );
            $this->leaflet->add_marker($marker);
			
			$marker = array(
				'latlng' 		=>'3.111965,98.4734686', // Marker Location
				'popupContent' 	=> 'Aloha PupUP !! COY !!', // Popup Content
				);
				$this->leaflet->add_marker($marker);
			
       
        	$data['map'] =  $this->leaflet->create_map();


		
        	$this->load->view("admin/dashboard",$data);
	}
}
