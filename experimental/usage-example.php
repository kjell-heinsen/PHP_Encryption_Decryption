<?php
// Code noch grundlegend ungetestet
// Beispiel zur Verwendung der SecureEncryption-Klasse

require_once 'SecureEncryption.php';

// Instanz der Klasse erstellen
$encryption = new SecureEncryption();

try {
    // Ein Beispieltext zum Verschlüsseln
    $sensitiveData = "Dies sind vertrauliche Informationen, die sicher verschlüsselt werden müssen.";
    
    // Ein sicheres Passwort verwenden (in der Produktion sollte dies sicher gespeichert werden)
    // Option 1: Ein vorhandenes Passwort
    $password = "MeinSicheresPasswort123!";
    
    // Option 2: Ein automatisch generiertes sicheres Passwort
    // $password = $encryption->generateSecurePassword(32);
    // echo "Generiertes Passwort: " . $password . "\n";
    
    // Daten verschlüsseln
    $encryptedData = $encryption->encrypt($sensitiveData, $password);
    echo "Verschlüsselte Daten: " . $encryptedData . "\n\n";
    
    // Daten entschlüsseln
    $decryptedData = $encryption->decrypt($encryptedData, $password);
    echo "Entschlüsselte Daten: " . $decryptedData . "\n";
    
    // Überprüfen, ob die Entschlüsselung erfolgreich war
    if ($decryptedData === $sensitiveData) {
        echo "\nErfolgreich! Die Daten wurden korrekt entschlüsselt.\n";
    } else {
        echo "\nFehler! Die entschlüsselten Daten stimmen nicht mit den Originaldaten überein.\n";
    }
    
    // Demonstrieren, dass falsches Passwort einen Fehler verursacht
    try {
        $wrongPassword = "FalschesPasswort";
        $decryptedDataWithWrongPassword = $encryption->decrypt($encryptedData, $wrongPassword);
        echo "Dies sollte nicht angezeigt werden, da ein Fehler erwartet wird.\n";
    } catch (Exception $e) {
        echo "\nErwarteter Fehler mit falschem Passwort: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "Fehler: " . $e->getMessage();
}
