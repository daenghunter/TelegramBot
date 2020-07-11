<?php

namespace config;

// 'production' jika ada error tidak akan muncul, 'development' error akan muncul.
$MODE   = 'production';

// token bot telegarm anda masukan di sini
$TOKEN  = '';

// masukan id telegram anda di sini
$ADMIN = '';

// set sesuai database anda
$DB = [
    'db_server'   => 'localhost', // server database
    'db_name'     => '', // nama database
    'db_username' => '', // username database
    'db_password' => '', // password database
];

// user agent
$AGENT  = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : 'Safari/534.30';

switch ($MODE) {
    case 'development':
        error_reporting(E_ALL); // <-- display errors true -->
        ini_set('display_errors', '1'); // <-- display errors true -->
        defined('DEBUG') || define('DEBUG', TRUE); // <-- debug response true -->
        break;
    case 'production':
        error_reporting(0); // <-- display errors false -->
        ini_set('display_errors', '0'); // <-- display errors false -->
        defined('DEBUG') || define('DEBUG', FALSE); // <-- debug response false -->
        break;
    default:
        die('Unknown mode');
}

// api key telegram
defined('TOKEN')    || define('TOKEN', $TOKEN); // <-- bot token -->
// admin id
defined('ADMIN_ID') || define('ADMIN_ID', $ADMIN); // <-- bot token -->
// user agent
defined('AGENT')    || define('AGENT', $AGENT); // <-- user agent -->
// database
defined('DB')       || define('DB', $DB); // <-- database -->