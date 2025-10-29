<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Pendaftaran class
 *
 * @author agusnur
 * Created : 05 Aug 2010
 *
 */

class tmpermohonan extends DataMapper {

    var $table = 'tmpermohonan';   

    var $has_one = array('tmpemohon', 'trperizinan', 'trjenis_permohonan','tmpemohon_sementara',
    'tmperusahaan', 'tmperusahaan_sementara', 'trsyarat_perizinan', 'trstspermohonan', 'tmbap',
        'tmsurat_permohonan', 'tmsurat_rekomendasi', 'tmsk', 'trtanggal_survey',
        'tmkeringananretribusi', 'tmsurat_keputusan', 'retribusi', 'trproyek');

    var $has_many = array('tmproperty_jenisperizinan', 'tmtrackingperizinan',
        'tmproperty_klasifikasi', 'tmproperty_prasarana', 'tmretribusi_rinci_imb', 'trsyarat_tambahan');

    public function __construct() {
        parent::__construct();
    }

}
