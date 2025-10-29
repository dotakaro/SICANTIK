<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Class untuk khusus API Mobile Apps
 * Created by PhpStorm.
 * User: core
 * Date: 1/28/2015
 * Time: 9:51 PM
 */

class Mobile_api extends REST_Controller{

    public function register_post(){
        // untuk request dari angular js (mobile)
        if($this->get('source') == 'mobile'){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);//Decode menjadi associative array
        }
        echo "<pre>";print_r($_POST);
    }

    public function login_post(){
        // untuk request dari angular js (mobile)
        if($this->get('source') == 'mobile'){
            $postdata = file_get_contents("php://input");
            $_POST = json_decode($postdata, true);//Decode menjadi associative array
        }

        echo "<pre>";print_r($_POST);
        //Ambil Username dan Password, cocokkan di database

        //Jika ada, baca data Pemohon dari User
    }

    /**
     * API GET untuk mendapatkan daftar permohonan dari seorang User
     * @author Indra
     */
    public function listPermohonanUser_get() {

        if (! $this->get('limit')) {
            $this->response(NULL, 400);
        }
        $limit = $this->get('limit');

        $query = "SELECT a.pendaftaran_id,c.n_pemohon, i.n_perizinan
            FROM tmpermohonan a
            INNER JOIN tmpemohon_tmpermohonan b ON a.id=b.tmpermohonan_id
            INNER JOIN tmpemohon c ON c.id=b.tmpemohon_id
            INNER JOIN tmpermohonan_trperizinan h ON h.tmpermohonan_id=a.id
            INNER JOIN trperizinan i ON i.id=h.trperizinan_id
            WHERE a.c_izin_selesai = 0 ORDER BY a.d_terima_berkas DESC LIMIT {$limit}";

        $result = $this->db->query($query)->result_array();
        if($result) {
            $this->response($result, 200);
        } else {
            $this->response(array('error' => 'Tidak ada data..'), 404);
        }
    }
} 