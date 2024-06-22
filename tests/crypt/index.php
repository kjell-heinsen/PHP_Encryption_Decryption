<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', 'php-errors.log');
if(file_exists('../../mainconfig.php')) {
    require_once '../../mainconfig.php';
}
require_once DOCROOT.'/tests/crypt/config.php';
require_once DOCROOT.'/crypt.php';

echo '---------- Beginne Crypt Test 1 Key Generierung ----------</br>';

echo 'Diese Keys müssen als definierte Werte Hinterlegt werden. (Siehe config.php)';
echo crypt::newkey();
echo '</br>';


echo '---------- Beginne Crypt Test 2 Verschlüsselung ----------</br>';
$plaintext = 'Dieser Text hat keine Bedeutung und dient als Text für die Verschlüsselung. Vielen Dank.';
echo 'Wir wollen nun folgenden Text verschlüsseln:</br>';
echo $plaintext.'</br>';
$encrypted = crypt::encrypt($plaintext);
echo 'Dies ist nun unser Verschlüsselter Text:</br>';
echo $encrypted.'</br>';

echo '---------- Beginne Crypt Test 3 Entschlüsselung ----------</br>';

$decrypted = crypt::decrypt($encrypted);
echo 'Dies ist nun unser Entschlüsselter Text:</br>';
echo $decrypted.'</br>';