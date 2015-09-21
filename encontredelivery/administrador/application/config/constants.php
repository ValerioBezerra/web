<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');
define('LAYOUT_DASHBOARD_ADMINISTRATIVO',		'dashboard');
define('MASCARA_FONE',		                    '(99) 99999999?9');
define('MASCARA_CPF',		                    '999.999.999-99');
define('MASCARA_CNPJ',		                    '99.999.999/9999-99');
define('MASCARA_CEP',		                    '99999-999');
define('MASCARA_HORA',		                    '99:99:99');
define('CHAVE_MD5',		                        '08787a804e2a4f7ba145a553e4eab7cb');
define("GOOGLE_API_KEY",                        'AIzaSyCwCt_8sOBtP-XAtcbxEnGhA5L2goqeAUc');



/* End of file constants.php */
/* Location: ./application/config/constants.php */