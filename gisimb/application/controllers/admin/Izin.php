<?php
Class Izin extends CI_Controller{

    var $API ="";

    function __construct() {
        parent::__construct();
        $this->API="http://perizinan.karokab.go.id/backoffice/api";
        $this->load->library('session');
        $this->load->library('curl');
        $this->load->helper('form');
        $this->load->helper('url');
    }

    // menampilkan data kontak
    function index(){
        $data['izin_terbit'] = json_decode($this->curl->simple_get('http://perizinan.karokab.go.id/backoffice/api/listpermohonanterbit/limit//10/offset//0'));
        $this->load->view('admin/izin/list_izin',$data);
    }

}