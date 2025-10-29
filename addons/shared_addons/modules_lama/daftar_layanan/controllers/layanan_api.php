<?php
/**
 * Class untuk API Mobile Apps Agam
 * Created by PhpStorm.
 * User: core
 * Date: 12/25/14
 * Time: 6:59 PM
 */
class layanan_api extends REST_Controller{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('daftar_layanan_m');
    }

    /**
     * Fungsi untuk mendapatkan daftar download di Aplikasi Mobile Agam
     */
    public function persyaratan_get() {
        $perizinanId = $this->input->get('perizinan_id');
        $data = $this->daftar_layanan_m->get_download_list($perizinanId);

        if($data) {
            $this->response($data, 200);
        } else {
            $this->response(NULL, 404);
        }
    }
} 