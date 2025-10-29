<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Model class for report component module
 * @author Indra Halim
 * @version 1.0
 */
class sms_masuk extends DataMapper
{
    var $table = 'sms_masuk';
    var $key_tracking = 'tracking';
    var $key_pertanyaan = 'pertanyaan';
    var $key_invalid = 'tidak valid';
    var $key_UAT = 'UAT';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Fungsi menterjemahkan SMS ke tipe Pertanyaan atau Tracking
     */
    public function parse_sms($rawSms = '')
    {
        $result = array();
//        $matches = preg_split("/[\s,]+/", $rawSms);//Split dengan karakter spasi atau comma
        $matches = preg_split("/[#]+/", $rawSms);//Split dengan karakter #
        if ($matches >= 2) {
            switch (strtoupper($matches[0])) {
                case 'TANYA'://Format untuk Pertanyaan : TANYA#NAMA#PERTANYAAN
                    $arrPertanyaan = array();
                    $totalMatches = count($matches);
                    $result['tipe_sms'] = $this->key_pertanyaan;
                    $result['nama'] = $matches[1];//kata ke 2 dianggap nama

                    ## BEGIN - Proses menggabungkan isi pertanyaan
                    ## Kata ke 3 dan seterusnya dianggap pertanyaan
                    for ($x = 2; $x < $totalMatches; $x++) {
                        $arrPertanyaan[] = $matches[$x];
                    }
                    ## END - Proses menggabungkan isi pertanyaan

                    $result['isi_sms'] = implode($arrPertanyaan, ' ');//Menggabungan pertanyaan dengan menggunakan spasi
                    $result['no_pendaftaran'] = null;
                    break;
                case 'TRACK'://Format Untuk Tracking : TRACK#NOPENDAFTARAN
                    $result['tipe_sms'] = $this->key_tracking;
                    $result['nama'] = null;
                    $result['isi_sms'] = null;
                    $result['no_pendaftaran'] = $matches[1];//Kata ke 2 dianggap no pendaftaran
                    break;
            }
        }
        return $result;
    }

    public function send_sms($message = '', $destination_no){
        $env = 'UAT';
        $this->config->load('sms_gateway',TRUE);
        $env = $this->config->item('environment', 'sms_gateway');
        $gateway_email = $this->config->item('gateway_email', 'sms_gateway');
        $testing_number = $this->config->item('testing_number', 'sms_gateway');
        if($env == $this->key_UAT){//Jika UAT
            $destination_no = $testing_number;//[TESTING] Hardcode agar tidak terkirim ke sebenarnya
        }
        $CI = get_instance();
        $CI->load->model('notification_setting/setting_notifikasi');
        $setting_notifikasi = new setting_notifikasi();
        if(!$setting_notifikasi->send_email($message, $destination_no, $gateway_email,'text')){
            return false;
        }else{
            return true;
        }
    }
}
?>