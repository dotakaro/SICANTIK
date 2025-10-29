<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!defined("DS"))
    define( "DS", DIRECTORY_SEPARATOR);
/**
 * Model class for Setting Notifikasi
 * @author Indra Halim
 * @version 1.0
 */
class setting_notifikasi extends DataMapper {
    var $table = 'setting_notifikasi';
    var $has_one = array('trperizinan');
    var $has_many = array('setting_notifikasi_detail');
    var $key_UAT = 'UAT';
    private $_SMTPConfig = array();

    public function __construct() {
        parent::__construct();
    }

    public function send_notification($trstspermohonan_id, $tmpermohonan_id){
        $env = 'UAT';
        $success = false;
        $num_failed = 0;
        $this->load->model('pelayanan/tmpermohonan');
        $this->tmpermohonan = new tmpermohonan();
        $permohonan = $this->tmpermohonan->get_by_id($tmpermohonan_id);

        $this->config->load('sms_gateway',TRUE);
        $gateway_email = $this->config->item('gateway_email', 'sms_gateway');
        $testing_number = $this->config->item('testing_number', 'sms_gateway');
        $testing_email = $this->config->item('testing_email', 'sms_gateway');
        $send_notification = $this->config->item('send_notification', 'sms_gateway');
        $env = $this->config->item('environment', 'sms_gateway');

        if($send_notification){//Jika menggunakan notifikasi
            if($permohonan->id){
                //Ambil Setting Notifikasi untuk Proses tersebut
                $this->where('trperizinan_id', $permohonan->trperizinan->id);
                $setting_notifikasi = $this->get();

                if($setting_notifikasi->id){
                    $message = '';
                    $setting_notifikasi_details = $setting_notifikasi->setting_notifikasi_detail
                                                    ->where('trstspermohonan_id', $trstspermohonan_id)->get();
                    if($setting_notifikasi_details->id){
                        foreach($setting_notifikasi_details as $setting_notifikasi_detail){//Looping, karena ada notifikasi ke Pemohon dan Petugas
                            //Ambil Format Email yang ingin dikirim
                            $message = $setting_notifikasi_detail->format_pesan;
                            $penerima_lain = $setting_notifikasi_detail->penerima_lain;
                            $arr_penerima_lain = explode(',', trim($penerima_lain));
                            $tipe_notifikasi = $setting_notifikasi_detail->tipe_notifikasi;

                            //Baca setting notifikasi untuk izin dengan id $trperizinan_id
                            switch($tipe_notifikasi){
                                case 'sms'://SMS
                                    $destination_numbers = array();
                                    $message = strip_tags($message);//Menghilangkan tag html dari sms
    //                                $to = 'batrasms@gmail.com';//[SETTING] nanti diganti dengan email penampung untuk sms gateway
                                    $to = $gateway_email;//[SETTING] nanti diganti dengan email penampung untuk sms gateway
                                    if(!empty($arr_penerima_lain)){
                                        $destination_numbers = array_merge($destination_numbers, $arr_penerima_lain);
                                    } /*sementara diremark agar tidak terkirim ke aslinya*/

                                    switch($setting_notifikasi_detail->tujuan_notifikasi){
                                        case 1://Notifikasi ke Pemohon
                                            $pemohon = $permohonan->tmpemohon->get();

                                            //Ambil No Telp Pemohon
                                            if(isset($pemohon->telp_pemohon) && !empty($pemohon->telp_pemohon)){
                                                $destination_numbers[] = $pemohon->telp_pemohon;
                                            }

                                            //Parsing Message
                                            $translated_message = $this->parse_message($message, $tmpermohonan_id, null, null, true);
                                            if(!empty($destination_numbers)){
                                                $email_sent = false;
                                                foreach($destination_numbers as $index=>$destination_no){
                                                    if(!is_null($destination_no) && $destination_no != ''){
                                                        if($env == $this->key_UAT){//Jika UAT
                                                            $translated_message .= '- Original To : '.$destination_no.'-';//[TESTING] Untuk Keperluan Debug
                                                            $destination_no = $testing_number;//[TESTING] Harcode agar tidak terkirim ke sebenarnya
                                                        }
                                                        if(!$this->send_email($translated_message, $destination_no, $to)){
                                                            $num_failed++;
                                                        }else{
                                                            $email_sent = true;
                                                        }
                                                    }
                                                }
                                                if($email_sent && $num_failed == 0){//Jika tidak ada message yang gagal terkirim, berarti berhasil
                                                    $success = true;
                                                }
                                            }
                                            break;
                                        case 2://Notifikasi ke Pegawai
                                            $this->load->model('unitkerja/trunitkerja');
                                            $this->trunitkerja = new trunitkerja();
                                            $email_sent = false;

                                            //BEGIN - Kirim Email untuk setiap Tim Teknis
                                            $tim_teknis = $permohonan->trtanggal_survey->tim_teknis->get();
                                            foreach($tim_teknis as $tim){//Looping setiap Tim Teknis
                                                $trunitkerja_id = $tim->trunitkerja_id;
                                                $unitkerja = $this->trunitkerja->get_by_id($trunitkerja_id);//Ambil Data Unit Kerja dari Tim Teknis
                                                if($unitkerja->id){
                                                    $nama_unitkerja = $unitkerja->n_unitkerja;
                                                    $all_pegawai = $unitkerja->tmpegawai->get();//ambil semua pegawai yang ada di Unit Kerja tersebut
                                                    if($all_pegawai->id){//Jika data ditemukan
                                                        foreach($all_pegawai as $pegawai){//Looping semua Pegawai
                                                            if(is_null($pegawai->no_telp) || $pegawai->no_telp == ''){
                                                                continue;//Jika tidak ada no telp, skip
                                                            }
                                                            $nama_pegawai = $pegawai->n_pegawai;
                                                            $no_telp = $pegawai->no_telp;
                                                            $email = $pegawai->email;
                                                            $translated_message = $this->parse_message($message, $tmpermohonan_id, $nama_pegawai, $nama_unitkerja, true);
                                                            if($env == $this->key_UAT){//Jika UAT
                                                                $translated_message .= '- Original To : '.$no_telp.'-';//[TESTING] Untuk Keperluan Debug
                                                                $no_telp = $testing_number;//[TESTING] hardcode agar tidak terkirim ke sebenarnya
                                                            }
                                                            if(!$this->send_email($translated_message, $no_telp, $to)){
                                                                $num_failed++;
                                                            }else{
                                                                $email_sent = true;
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                            //END - Kirim Email untuk setiap Tim Teknis

                                            //BEGIN - Kirim ke Nomor lain jika ada
                                            /*if(!empty($destination_numbers)){
                                                foreach($destination_numbers as $index=>$destination_no){
                                                    if(!$this->send_email($translated_message, $destination_no, $to)){
                                                        $num_failed++;
                                                    }
                                                }
                                            }*/
                                            //END - Kirim ke Nomor lain jika ada

                                            if($email_sent && $num_failed == 0){//Jika tidak ada message yang gagal terkirim, berarti berhasil
                                                $success = true;
                                            }
                                            break;
                                    }
                                    break;
                                default://Email
                                    $destination_emails = array();
                                    if(!empty($arr_penerima_lain)){
                                        $destination_emails = $arr_penerima_lain;
                                    } /*sementara diremark agar tidak terkirim ke aslinya*/

                                    switch($setting_notifikasi_detail->tujuan_notifikasi){
                                        case 1://Notifikasi ke Pemohon
                                            $pemohon = $permohonan->tmpemohon->get();
                                            if($pemohon->id){
                                                if($pemohon->email_pemohon != '' && !is_null($pemohon->email_pemohon)){
                                                    $destination_emails[] = $pemohon->email_pemohon;
                                                }
                                            }

                                            //Parsing message body
                                            $translated_message = $this->parse_message($message, $tmpermohonan_id);
                                            if(!empty($destination_emails)){
                                                foreach($destination_emails as $index=>$destination_email){
                                                    if($destination_email != '' && !is_null($destination_email)){
                                                        $subject = '[Notifikasi]';
                                                        if($env == $this->key_UAT){ //Jika UAT
                                                            $translated_message .= '- Original To : '.$email.'-';//[TESTING] Untuk Keperluan Debug
                                                            $destination_email = $testing_email;//[TESTING] agar tidak terkirim ke email sebenarnya
                                                        }
                                                        if(!$this->send_email($translated_message, $subject, $destination_email, 'html')){
                                                            $num_failed++;
                                                        }
                                                    }
                                                }
                                                if($num_failed == 0){//Jika tidak ada message yang gagal terkirim, berarti berhasil
                                                    $success = true;
                                                }
                                            }
                                            break;
                                        case 2://Notifikasi ke Pegawai
                                            $this->load->model('unitkerja/trunitkerja');
                                            $this->trunitkerja = new trunitkerja();

                                            //BEGIN - Kirim Email untuk setiap Tim Teknis
                                            $tim_teknis = $permohonan->trtanggal_survey->tim_teknis->get();
                                            foreach($tim_teknis as $tim){//Looping setiap Tim Teknis
                                                $trunitkerja_id = $tim->trunitkerja_id;
                                                $unitkerja = $this->trunitkerja->get_by_id($trunitkerja_id);//Ambil Data Unit Kerja dari Tim Teknis
                                                if($unitkerja->id){
                                                    $nama_unitkerja = $unitkerja->n_unitkerja;
                                                    $all_pegawai = $unitkerja->tmpegawai->get();//ambil semua pegawai yang ada di Unit Kerja tersebut
                                                    if($all_pegawai->id){//Jika data ditemukan
                                                        foreach($all_pegawai as $pegawai){//Looping semua Pegawai
                                                            if(is_null($pegawai->email) || $pegawai->email == ''){
                                                                continue;//Jika tidak ada no telp, skip
                                                            }
                                                            $nama_pegawai = $pegawai->n_pegawai;
                                                            $no_telp = $pegawai->no_telp;
                                                            $email = $pegawai->email;
                                                            $translated_message = $this->parse_message($message, $tmpermohonan_id, $nama_pegawai, $nama_unitkerja);
                                                            if($env == $this->key_UAT){//Jika UAT
                                                                $translated_message .= '- Original To : '.$email.'-';//[TESTING] Untuk Keperluan Debug
                                                                $destination_email = $testing_email;//[TESTING]
                                                            }
                                                            $subject = '[Notifikasi]';
                                                            if(!$this->send_email($translated_message, $subject, $destination_email, 'html')){
                                                                $num_failed++;
                                                            }
                                                        }
                                                    }
                                                }

                                            }
                                            //END - Kirim Email untuk setiap Tim Teknis

                                            //Parsing message body
                                            /*$translated_message = $this->parse_message($message, $tmpermohonan_id);
                                            if(!empty($destination_emails)){
                                                foreach($destination_emails as $index=>$destination_email){
                                                    $subject = '[Notifikasi]';
                                                    if(!$this->send_email($translated_message, $subject, $destination_email, 'html')){
                                                        $num_failed++;
                                                    }
                                                }
                                                if($num_failed == 0){//Jika tidak ada message yang gagal terkirim, berarti berhasil
                                                    $success = true;
                                                }
                                            }*/

                                            if($num_failed == 0){//Jika tidak ada message yang gagal terkirim, berarti berhasil
                                                $success = true;
                                            }
                                            break;
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
        }

        return $success;
    }

    /**
     * Fungsi untuk parsing format message menjadi message yang akan dikirimkan
     * @param $message_body
     * @param $trperizinan_id
     * @return $parsed_message
     */
    public function parse_message($message_body, $tmpermohonan_id = null, $nama_pegawai = null, $nama_unitkerja = null, $filterMessage = false){
        $parsed_message = '';
        $this->load->model('pelayanan/tmpermohonan');
        $this->tmpermohonan = new tmpermohonan();
        $permohonan = $this->tmpermohonan->get_by_id($tmpermohonan_id);
        $pemohon = $permohonan->tmpemohon->get();
        $nama_pemohon = $pemohon->n_pemohon;
        $telp_pemohon = $pemohon->telp_pemohon;
        $no_pendaftaran = $permohonan->pendaftaran_id;

        eval("\$parsed_message = \"$message_body\";");
        if ($filterMessage) {
            $parsed_message = str_replace('&nbsp;',' ',$parsed_message);
        }

        return $parsed_message;
    }

    public function send_email2($message, $subject, $to, $type='text'){
        $this->load->library('email');
        $config = array();

        $this->config->load('sms_gateway',TRUE);
        $smtp_host = $this->config->item('smtp_host', 'sms_gateway');
        $smtp_port = $this->config->item('smtp_port', 'sms_gateway');
        $smtp_user = $this->config->item('smtp_user', 'sms_gateway');
        $smtp_pass = $this->config->item('smtp_pass', 'sms_gateway');
        $gateway_email = $this->config->item('gateway_email', 'sms_gateway');

        ### BEGIN - Config Gmail ###
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = $smtp_host;
        $config['smtp_port'] = $smtp_port;
        $config['smtp_user'] = $smtp_user;
        $config['smtp_pass'] = $smtp_pass;

//        $config['charset'] = 'iso-8859-1';
        $config['mailtype']	 = $type;//text atau html
        $config['charset'] = 'utf-8';
        ### END   - Config Gmail ###

        $this->email = new CI_Email($config);
        $this->email->set_newline("\r\n");

        //Ambil Data Perizinan untuk detail pengiriman email
        $this->email->from($gateway_email, 'Backoffice');
        $this->email->to($to);
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $this->email->subject($subject);
        $this->email->message($message);
        if ( ! $this->email->send())
        {
            return false;//Gagal mengirim email
        }else{
            return true;
        }
    }

    /**
     * Fungsi untuk menyiapkan config SMTP agar tidak load berkali-kali
     */
    private function _prepareSMTP(){
        if(empty($this->_SMTPConfig)){
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
            $this->config->load('sms_gateway',TRUE);
            $this->_SMTPConfig['smtp_host'] = $this->config->item('smtp_host', 'sms_gateway');
            $this->_SMTPConfig['smtp_port'] = $this->config->item('smtp_port', 'sms_gateway');
            $this->_SMTPConfig['smtp_user'] = $this->config->item('smtp_user', 'sms_gateway');
            $this->_SMTPConfig['smtp_pass'] = $this->config->item('smtp_pass', 'sms_gateway');
            //$gateway_email = $this->config->item('gateway_email', 'sms_gateway');

            $pathLibrary = '.'.DS.'www'.DS.'libraries';
            require_once $pathLibrary.DS.'PHPMailer-master'.DS.'class.phpmailer.php';
            require_once $pathLibrary.DS.'PHPMailer-master'.DS.'class.pop3.php';
            require_once $pathLibrary.DS.'PHPMailer-master'.DS.'class.smtp.php';
        }
    }

    public function send_email($message, $subject, $to, $type='text'){
        $this->_prepareSMTP();
        $smtp_host = $this->_SMTPConfig['smtp_host'];
        $smtp_port = $this->_SMTPConfig['smtp_port'];
        $smtp_user = $this->_SMTPConfig['smtp_user'];
        $smtp_pass = $this->_SMTPConfig['smtp_pass'];

        $mail = new PHPMailer();
        $mail->isSMTP();                       // telling the class to use SMTP
        $mail->SMTPDebug = 0;
        // 0 = no output, 1 = errors and messages, 2 = messages only.

        $mail->SMTPAuth = true;                // enable SMTP authentication
//        $mail->SMTPSecure = "";              // sets the prefix to the servier
        $mail->SMTPSecure = "tls";              // sets the prefix to the servier
        $mail->Host = $smtp_host;        // sets Gmail as the SMTP server
        $mail->Port = $smtp_port;                     // set the SMTP port for the GMAIL
        $mail->Username = $smtp_user;  // Gmail username
        $mail->Password = $smtp_pass;      // Gmail password

        $mail->setFrom ($smtp_user, 'Backoffice');
//        $mail->AddBCC ( 'sales@example.com', 'Example.com Sales Dep.');
        $mail->Subject = $subject;
        $mail->ContentType = 'text/plain';
        $mail->isHTML(false);
        /*echo $smtp_host."<br>";
        echo $smtp_user."<br>";
        echo $smtp_pass."<br>";
        echo $smtp_port."<br>";
        echo $to."<br>";
        echo $subject."<br>";
        echo $message."<br>";*/
        $mail->Body = $message;
        // you may also use $mail->Body = file_get_contents('your_mail_template.html');

        $mail->addAddress ($to, 'SMS Gateway');
        // you may also use this format $mail->AddAddress ($recipient);

        if(!$mail->Send())
        {
            $error_message = "Mailer Error: " . $mail->ErrorInfo;
            echo $error_message;
            return false;//Gagal mengirim email
        } else
        {
            $error_message = "Successfully sent!";
            echo $error_message;
            return true;
        }
//        exit();
    }
}