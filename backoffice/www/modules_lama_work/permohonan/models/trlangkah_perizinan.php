<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of tmsurat_rekomendasi class
 *
 * @author agusnur
 * Created : 04 Sep 2010
 *
 */

class trlangkah_perizinan extends DataMapper {

    var $table = "trlangkah_perizinan";
    var $has_one = array('trstspermohonan', 'trkelompok_perizinan');

    public function __construct() {
        parent::__construct();
    }


/*$this->load->model('permohonan/trlangkah_perizinan');
$langkah_perizinan = new trlangkah_perizinan();
$status_izin = $permohonan->trstspermohonan->get();
$next_status = $langkah_perizinan->nextStep($permohonan->trperizinan->trkelompok_perizinan->id, $status_izin->id);*/

    public function nextStep($trkelompok_perizinan_id, $trstspermohonan_id){
        $ret = null;

        //1. Ambil No Urut dari Langkah sekarang
        $current_step = $this->where('trkelompok_perizinan_id', $trkelompok_perizinan_id)
                            ->where('trstspermohonan_id', $trstspermohonan_id)->get();
        if($current_step->id){
            //2. Ambil Langkah selanjutnya untuk kelompok izin tersebut
            $next_step = $this->where('trkelompok_perizinan_id', $trkelompok_perizinan_id)
                                ->where('urut >', $current_step->urut)
                                ->order_by('urut','ASC')->get();
            if($next_step->id){
                return $next_step->trstspermohonan_id;
            }
        }else{
            //3. Ambil Langkah Pertama untuk kelompok izin tersebut
            $next_step = $this->where('trkelompok_perizinan_id', $trkelompok_perizinan_id)
                ->order_by('urut','ASC')->get();
            if($next_step->id){
                return $next_step->trstspermohonan_id;
            }
        }
    }
}

// This is the end of tmsurat_rekomendasi class
