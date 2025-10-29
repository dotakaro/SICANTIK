<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* App Koneksi Generate By sistem - Docker Environment */
$active_group = 'default';
$active_record = TRUE;
$db['default']['hostname'] = 'sicantik_mysql';
$db['default']['username'] = 'sicantik_user';
$db['default']['password'] = 'sicantik_password';
$db['default']['database'] = 'db_office_last';

$_SESSION['my_db']=$db['default']['database'];
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = FALSE;
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
