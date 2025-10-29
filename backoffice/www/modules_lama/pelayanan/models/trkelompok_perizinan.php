<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author agusnur
 * Created : 06 Aug 2010
 *
 */

class trkelompok_perizinan extends DataMapper {

    var $table = 'trkelompok_perizinan';

    var $has_many = array('trperizinan','trlangkah_perizinan');

    var $listMandatoryStatus = array(
        1,//Pendaftaran Sementara
        2,//Menerima dan Memeriksa Berkas,
        3,//Entri data
        //4,//Penjadwalan Tinjauan //Dilepas atas Request bu Shinta
//        5,//Rekomendasi
        //6,//Pembuatan BAP //Dilepas atas Request bu Shinta
//        7,//Penetapan Izin
//        8,//Diizinkan
//        10,//Menetapkan Retribusi dan Mencetak SKRD
//        12,//Memberi Nomor dan Mencetak Surat
//        13,//Kasir
        14,//Penyerahan Izin
//        15,//Arsip
//        16,//Izin Dicabut
        17,//Pembuatan Izin
//        18,//Perhitungan Retribusi,
//        19,//Entry Hasil Tinjauan
    );

    public function __construct() {
        parent::__construct();
    }

    public function insertKelompokPerizinan($data, $dataLangkahPerizinan){
        $success = $this->db->insert('trkelompok_perizinan', $data);
        if(!empty($dataLangkahPerizinan)){
            $idKelompokIzin = $this->getLastInsertId();
            foreach($dataLangkahPerizinan as $index=>$langkah){
                $langkah['trkelompok_perizinan_id'] = $idKelompokIzin;
                $this->db->insert('trlangkah_perizinan', $langkah);
            }
        }
        return $success;
    }

    public function updateKelompokPerizinan($idKelompokIzin, $data,  $dataLangkahPerizinan){
        if(!empty($dataLangkahPerizinan)){
            $this->deleteLangkahPerizinan($idKelompokIzin);
            foreach($dataLangkahPerizinan as $index=>$langkah){
//                echo "<pre>";print_r($dataLangkahPerizinan);exit();
                $this->db->insert('trlangkah_perizinan', $langkah);
            }
        }
        $this->db->where('id', $idKelompokIzin);
        return $this->db->update('trkelompok_perizinan', $data);
    }

    public function deleteKelompokPerizinan($idKelompokIzin){
        $this->db = $this->load->database('default', TRUE);
        $this->db->where('id', $idKelompokIzin);
        return $this->db->delete('trkelompok_perizinan');
    }

    public function deleteLangkahPerizinan($idKelompokIzin){
        $this->db = $this->load->database('default', TRUE);
        $this->db->where('trkelompok_perizinan_id', $idKelompokIzin);
        return $this->db->delete('trlangkah_perizinan');
    }

    public function getLastInsertId(){
        return $this->db->insert_id();
    }

}

// This is the end of user class
