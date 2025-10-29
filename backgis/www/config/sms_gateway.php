<?php
/**
 * Created by PhpStorm.
 * User: core
 * Date: 1/15/2015
 * Time: 10:14 PM
 */
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//$config['smtp_host'] = 'ssl://smtp.googlemail.com';//SMTP Host untuk mengirim email berisi SMS
//$config['smtp_port'] = 465;//SMTP Post untuk mengirim email berisi SMS
//$config['smtp_user'] = 'batrasms';//SMTP User untuk mengirim email berisi SMS
//$config['smtp_pass'] = 'batrasms123';//SMTP Password untuk mengirim email berisi SMS

/*$config['smtp_host'] = 'smtp.agamkab.go.id';//SMTP Host untuk mengirim email berisi SMS
$config['smtp_port'] = 587;//SMTP Post untuk mengirim email berisi SMS
$config['smtp_user'] = 'kpmpt@agamkab.go.id';//SMTP User untuk mengirim email berisi SMS
$config['smtp_pass'] = '12345678a';//SMTP Password untuk mengirim email berisi SMS
$config['gateway_email'] = 'kmpt.agam@batralanggeng.com';//Email yang akan menampung SMS Outbox*/

//[BEGIN] Config Batralanggeng
//harus aktifkan tls saat mengirim email
$config['smtp_host'] = 'mail.batralanggeng.com';//SMTP Host untuk mengirim email berisi SMS
$config['smtp_port'] = 587;//SMTP Post untuk mengirim email berisi SMS
$config['smtp_user'] = 'kmpt.agam@batralanggeng.com';//SMTP User untuk mengirim email berisi SMS
$config['smtp_pass'] = 'agamtes1234';//SMTP Password untuk mengirim email berisi SMS
$config['gateway_email'] = 'kmpt.agam@batralanggeng.com';//Email yang akan menampung SMS Outbox
//$config['gateway_email'] = 'batrasms@gmail.com';//Email yang akan menampung SMS Outbox
$config['use_forwarder'] = true;//Jika true maka notifikasi akan akses web service untuk mengirim email
//$config['api_forwarder'] = 'http://localhost/sicantik-agam/backoffice/api/sendemail';//Api untuk mengirim Email sms
$config['api_forwarder'] = 'http://batralanggeng.com/kpmpt_agam/api/sendemail';//Api untuk mengirim Email sms
//[END] Config Batralanggeng

//Untuk Testing
$config['testing_number'] = '01234';//Nomor Tujuan Notifikasi SMS untuk Testing Notifikasi bertipe SMS
$config['testing_email'] = 'batrasms@gmail.com';//Email Tujuan Notifikasi Email untuk Testing Notifikasi bertipe Email
$config['environment'] = 'PROD';//[UAT/PROD] Jika UAT, tidak akan dikirim ke nomor sebenarnya
$config['send_notification'] = true;//Jika true, maka setiap proses pengajuan izin akan ada notifikasi email
?>
