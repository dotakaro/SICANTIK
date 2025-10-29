<?php

/**
 * Created Ade Tri Putra
 */


if (!defined('BASEPATH'))
    exit('No direct script access allowed');

//harus aktifkan SSL saat mengirim email
$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = 'smtp.gmail.com';
$config['smtp_port'] = 465;
$config['smtp_user'] = 'dpmpptspkaro1@gmail.com';
$config['smtp_pass'] = 'sdzbaagjnbulmspk';
$config['mailtype'] = 'html';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;
