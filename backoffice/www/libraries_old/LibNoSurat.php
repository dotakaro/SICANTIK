<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of LibNoSurat class
 *
 * @author  Dichi Al Faridi
 * @since   1.0
 *
 */

class LibNoSurat {
    //put your code here

    public function  __construct() {
        $this->CI = & get_instance();
    }

    public function getNumber($type = NULL, $id = NULL) {
        $data_urut = NULL;
        $data_id = NULL;
        $code = NULL;
        $isIncNoUrut = $this->_isInc($id);
        if($type === NULL) {
            $type === 'survey';
        }

        switch ($type) {
            case 'survey':
                    $data_id = new trtanggal_survey();
                    $code = "TL";
                break;
        }

        $data_id->select_max('id')->get();
        $data_id->get_by_id($data_id->id);
        if($isIncNoUrut) {
            $data_urut = $data_id->i_urut + 1;
        } else {
            $data_urut = 1;
        }

        $i_urut = strlen($data_urut);
        for($i=4;$i>$i_urut;$i--){
            $data_urut = "0".$data_urut;
        }

        $data_izin = $this->_getPerizinan($id);
        $i_izin = strlen($data_izin);
        for($i=3;$i>$i_izin;$i--){
            $data_izin = "0".$data_izin;
        }

        $lib_date = new Lib_date();
        $data_bulan = $lib_date->set_month_roman(date("n"));
        $no_surat = $data_urut . "/" . $code . "/" . $data_izin
            ."/" . $data_bulan . "/" . $this->_getYear();

        return $no_surat;
    }

    private function _getPerizinan($id = NULL) {
        $permohonan = new tmpermohonan();
        $permohonan->where('id',$id)->get();
        return $permohonan->id;
    }

    private function _getPermohonanYear($id = NULL) {
        $permohonan = new tmpermohonan();
        $permohonan->where('id',$id)->get();
        return $permohonan->d_tahun;
    }

    private function _getYear() {
        return date("Y");
    }

    private function _isInc($id = NULL) {
        if($this->_getYear() === $this->_getPermohonanYear($id)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

// This is the end of LibNoSurat class
