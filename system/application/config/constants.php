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

define('FOPEN_READ', 							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 					'ab');
define('FOPEN_READ_WRITE_CREATE', 				'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 			'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/* Begin custom UBP constants */

define("MIN_USERNAME_LENGTH",						3);
define("MAX_USERNAME_LENGTH",						20);
define("MIN_PASSWORD_LENGTH", 						5);
define("MAX_PASSWORD_LENGTH",						25);
define("MAX_TITLE_LENGTH",							150);
define("MAX_POST_LENGTH",							5000);
define("MAX_BLACKLIST_LIMIT",						20);
define("MAX_FEED_PAGE_SIZE",						25);
define("FEED_PAGE_SIZE_INCREMENT",					5);
define("SERVER_ERROR_MESSAGE",						"There was a server error.  Please try again later, or contact the webmaster (jeremyckahn@gmail.com).");
define("ATOM_FEED_SIZE",							20);

/* End of file constants.php */
/* Location: ./system/application/config/constants.php */