<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
ini_set('log_errors', 'On');
ini_set('error_log', 'php-errors.log');
if(file_exists('../../mainconfig.php')) {
    require_once '../../mainconfig.php';
}

require_once DOCROOT.'/tests/keycrypt/config.php';
require_once DOCROOT.'/keycrypt.php';


echo '---------- Beginne KeyCrypt Test 1 Key Generierung ----------</br>';
echo 'Generiere Key-Files mypub.key und mypriv.key</br>';
$pathtopublickeyfile = 'mypub.key'; // The key files in this folder are for illustrative purposes only and are not intended for productive operation. Please replace them.
$pathtoprivateKeyFile = 'mypriv.key';
keycrypt::createkeys($pathtoprivateKeyFile,$pathtopublickeyfile);

echo '---------- Beginne KeyCrypt Test 2 Verschlüsselung mit Public Key ----------</br>';
$plaintext = 'Ich mag diesen Text und daher schreibe ich einen Satz auf der keinen Sinn ergibt. Dieser muss auch lang genug sein, damit man ein bisschen was von der gesamten Logik sieht.';
echo 'Folgender Text ist Teil des Testes:</br>';
echo $plaintext.'</br>';

try {
    $encrypted = keycrypt::encrypt($plaintext,$pathtopublickeyfile);
} catch(\Exception $e){
    echo $e->getMessage();
}
echo 'Dies ist nun der verschlüsselte Text:</br>';
echo $encrypted;

echo '</br>';

echo '---------- Beginne KeyCrypt Test 3 Entschlüsselung mit Private Key ----------</br>';

try {
    $decrypted = keycrypt::decrypt($encrypted,$pathtoprivateKeyFile);
} catch(\Exception $e){
    echo $e->getMessage();
}

echo 'Dies ist nun der entschlüsselte Text:</br>';
echo $decrypted;
echo '</br>';


echo '---------- Beginne KeyCrypt Test 3 Verschlüsselung mit Private Key ----------</br>';

try {
    $encrypted = keycrypt::encrypt($plaintext,$pathtoprivateKeyFile,false);
} catch(\Exception $e){
    echo $e->getMessage();
}
echo 'Dies ist nun der verschlüsselte Text:</br>';
echo $encrypted;

echo '</br>';

echo '---------- Beginne KeyCrypt Test 4 Entschlüsselung mit Public Key ----------</br>';

try {
    $decrypted = keycrypt::decrypt($encrypted,$pathtopublickeyfile,true);
} catch(\Exception $e){
    echo $e->getMessage();
}

echo 'Dies ist nun der entschlüsselte Text:</br>';
echo $decrypted;
echo '</br>';