<?php 
defined('BASEPATH') OR exit ('No Direct script access allowed');

class Peta extends CI_Controller {

    public function __construct()
    {
        parent:: __construct();
        $this->load->library('leaflet');
    }

    public function index()
	{

        $config = array(
            'center'         => '3.111965,98.4734686', // Center of the map
            'zoom'           => 14, // Map zoom
            );
        $this->leaflet->initialize($config);
        
        $marker = array(
            'latlng' 		=>'3.111965,98.4734686', // Marker Location
            'popupContent' 	=> 'Aloha PupUP !! COY !!', // Popup Content
            );
            $this->leaflet->add_marker($marker);
        
       
        $data['map'] =  $this->leaflet->create_map();


		$this->load->view('maphome');
	}

	public function about()
	{
		$this->load->view('about.php');
	}

	public function contact()
	{
		$this->load->view('contact.php');
	}
	
	public function bangunan_json()
	{
		$data=$this->db->get('bangunan')->result();
		echo json_encode($data);
	}




}