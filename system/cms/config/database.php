<?php defined('BASEPATH') OR exit('No direct script access allowed');

// Development
$db[PYRO_DEVELOPMENT] = array(
	'hostname'		=> 	'sicantik_mysql',
	'username'		=> 	'sicantik_user',
	'password'		=> 	'sicantik_password',
	'dbdriver' 		=> 	'mysqli',
    'database'		=> 	'db_office',
    'dbprefix' 		=>	'',
	'pconnect' 		=>	FALSE,
	'db_debug' 		=>	FALSE,
	'cache_on' 		=>	FALSE,
	'char_set' 		=>	'utf8',
	'dbcollat' 		=>	'utf8_unicode_ci',
	'port' 	 		=>	'3306',
	'stricton' 		=> FALSE,
);

// Production (same as development for Docker)
$db[PYRO_PRODUCTION] = array(
	'hostname'		=> 	'sicantik_mysql',
	'username'		=> 	'sicantik_user',
	'password'		=> 	'sicantik_password',
	'database'		=> 	'db_office',
	'dbdriver' 		=> 	'mysqli',
	'pconnect' 		=>	FALSE,
	'db_debug' 		=>	FALSE,
	'cache_on' 		=>	FALSE,
	'char_set' 		=>	'utf8',
	'dbcollat' 		=>	'utf8_unicode_ci',
	'port' 	 		=>	'3306',
);

// Check the configuration group in use exists
if ( ! array_key_exists(ENVIRONMENT, $db))
{
	show_error(sprintf(lang('error_invalid_db_group'), ENVIRONMENT));
}

// Assign the group to be used
$active_group = ENVIRONMENT;
$query_builder = TRUE;

/* End of file database.php */
