# PHP_Encryption_Decryption

* This class compilation is used to encode and decode strings.
* If something is noticeable, not secure enough or does not work, please report it.

## Example Class Crypt

* Generate 2 keys using the following line:
```php
crypt::newkey();  // Run the command and Take the Result and put it in the Follow Lines

define('FIRSTKEY', 'KeyofFirstLine');  // Save this Line in config file
define('SECONDKEY','KeyofSecondLine'); // Save this Line in config file

$yourtext = 'This is your Text and I like this.';

$encrypted = crypt::encrypt($yourtext); // Your Text is now encrypted

$decrypted = crypt::decrypt($encrypted); // Your Text is now decrypted

```




## Example Class KeyCrypt

* Set the length of the keys you want to use: (1024, 2048, 4096)

```php
// Set in Class keycrypt your Key Length
const mainlength = 4096; // or 1024 or 2048

$pathtopublickeyfile = 'mypublic.key';
$pathtoprivateKeyFile = 'myprivate.key';


keycrypt::createkeys($pathtoprivateKeyFile,$pathtopublickeyfile);  // After That you find your keys in the generated Files


$yourtext = 'This is your Text and I like this.';
try {
$encrypted = keycrypt::encrypt($yourtext,$pathtopublickeyfile);
} catch(\Exception $e){
  echo $e->getMessage();
}
echo $encrypted;


echo '</br>';

try {
$decrypted = keycrypt::decrypt($encrypted,$pathtoprivateKeyFile);
} catch(\Exception $e){
  echo $e->getMessage();
}

echo $decrypted;
```

* These classes do not claim to be perfect or the best solution. They are a possible solution.