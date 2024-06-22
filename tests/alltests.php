<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', 'php-errors.log');
if(file_exists('../mainconfig.php')) {
    require_once '../mainconfig.php';
}
require_once DOCROOT.'/tests/crypt/index.php';
require_once DOCROOT.'/tests/keycrypt/index.php';