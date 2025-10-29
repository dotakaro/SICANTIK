<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* App Koneksi Generate By sistem */
$active_group = 'default';
$active_record = TRUE;
$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = 'lingga';
$db['default']['database'] = 'db_office_last';

//$db['default']['database'] = 'backoffice_empty_with_property';

$_SESSION['my_db']=$db['default']['database'];
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
//$db['default']['pconnect'] = FALSE;
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
//$db['default']['db_debug'] = TRUE;//Agar error terlihat
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';

## BEGIN - Konfigurasi untuk Report Display
$db['report_display'] = $db['default'];
$db['report_display']['dbdriver'] = 'mysqli';
## END - Konfigurasi untuk Report Display


/* End of file database.php */
/* Location: ./system/application/config/database.php */
