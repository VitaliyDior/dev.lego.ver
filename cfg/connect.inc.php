<?php
	//database connection settings
	define('DB_HOST', 'localhost'); // database host
	define('DB_USER', 'root'); // username
	define('DB_PASS', ''); // password
	define('DB_NAME', 'dev'); // database name
	define('DB_CHARSET', 'utf8'); // database name
	define('ADMIN_LOGIN', 'YWRtaW4='); //administrator's login
	define('ADMIN_PASS', 'c4ca4238a0b923820dcc509a6f75849b'); //administrator's login
	//database tables
	include(dirname(__FILE__)."/tables.inc.php");
?>