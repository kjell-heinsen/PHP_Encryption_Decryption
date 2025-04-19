<?php

/**
 * SecureEncryption Class
 * 
 * Eine Klasse zur sicheren Verschlüsselung und Entschlüsselung von Daten 
 * unter Verwendung moderner kryptografischer Standards (AES-256-GCM).
 */
class SecureEncryption {
    /**
     * Die verwendete Verschlüsselungsmethode
     */
    private const CIPHER_METHOD = 'aes-256-gcm';
    
    /**
     * Anzahl der Iterationen für PBKDF2
     */
    private const PBKDF2_ITERATIONS = 100000;
    
    /**
     * Länge des Schlüssels in Bytes (256 bit)
     */
    private const KEY_LENGTH = 32;
    
    /**
     * Länge des Salts in Bytes
     */
    private const SALT_LENGTH = 16;
    
    /**
     * Länge des Initialization Vectors in Bytes
     */
    private const IV_LENGTH = 12; // Für GCM ist 12 Byte optimal
    
    /**
     * Länge des Auth Tags in Bytes
     */
    private const TAG_LENGTH = 16;
    
    /**
     * Verschlüsselt eine Nachricht mit einem Passwort
     *
     * @param string $message Die zu verschlüsselnde Nachricht
     * @param string $password Das Passwort zur Verschlüsselung
     * @return string Die verschlüsselte Nachricht, Base64-kodiert
     * @throws Exception Bei Fehlern in der Verschlüsselung
     */
    public function encrypt(string $message, string $password): string {
        // Überprüfen, ob die Verschlüsselungsmethode verfügbar ist
        if (!in_array(self::CIPHER_METHOD, openssl_get_cipher_methods())) {
            throw new Exception('Die Verschlüsselungsmethode ' . self::CIPHER_METHOD . ' wird nicht unterstützt.');
        }
        
        try {
            // Generiere ein sicheres, zufälliges Salt
            $salt = random_bytes(self::SALT_LENGTH);
            
            // Leite einen Schlüssel aus dem Passwort ab (PBKDF2)
            $key = $this->deriveKey($password, $salt);
            
            // Generiere einen sicheren, zufälligen Initialization Vector (IV)
            $iv = random_bytes(self::IV_LENGTH);
            
            // Führe die Verschlüsselung durch
            $tag = ''; // Auth-Tag wird hier gespeichert
            $ciphertext = openssl_encrypt(
                $message,
                self::CIPHER_METHOD,
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '', // AAD (Additional Authenticated Data) nicht verwendet
                self::TAG_LENGTH
            );
            
            if ($ciphertext === false) {
                throw new Exception('Verschlüsselung fehlgeschlagen: ' . openssl_error_string());
            }
            
            // Kombiniere Salt, IV, Tag und Ciphertext in einer Struktur
            $encrypted = $salt . $iv . $tag . $ciphertext;
            
            // Rückgabe als Base64-kodierten String
            return base64_encode($encrypted);
            
        } catch (Exception $e) {
            throw new Exception('Fehler bei der Verschlüsselung: ' . $e->getMessage());
        }
    }
    
    /**
     * Entschlüsselt eine verschlüsselte Nachricht mit einem Passwort
     *
     * @param string $encryptedMessage Die verschlüsselte Nachricht, Base64-kodiert
     * @param string $password Das Passwort zur Entschlüsselung
     * @return string Die entschlüsselte Nachricht
     * @throws Exception Bei Fehlern in der Entschlüsselung
     */
    public function decrypt(string $encryptedMessage, string $password): string {
        try {
            // Dekodiere den Base64-String
            $encrypted = base64_decode($encryptedMessage, true);
            
            if ($encrypted === false) {
                throw new Exception('Ungültiger Base64-String.');
            }
            
            // Überprüfe, ob die verschlüsselte Nachricht lang genug ist
            $minLength = self::SALT_LENGTH + self::IV_LENGTH + self::TAG_LENGTH + 1;
            if (strlen($encrypted) < $minLength) {
                throw new Exception('Die verschlüsselte Nachricht ist zu kurz.');
            }
            
            // Extrahiere Salt, IV, Tag und Ciphertext
            $salt = substr($encrypted, 0, self::SALT_LENGTH);
            $iv = substr($encrypted, self::SALT_LENGTH, self::IV_LENGTH);
            $tag = substr($encrypted, self::SALT_LENGTH + self::IV_LENGTH, self::TAG_LENGTH);
            $ciphertext = substr($encrypted, self::SALT_LENGTH + self::IV_LENGTH + self::TAG_LENGTH);
            
            // Leite den Schlüssel aus dem Passwort und Salt ab
            $key = $this->deriveKey($password, $salt);
            
            // Entschlüssele die Nachricht
            $decrypted = openssl_decrypt(
                $ciphertext,
                self::CIPHER_METHOD,
                $key,
                OPENSSL_RAW_DATA,
                $iv,
                $tag,
                '' // AAD (Additional Authenticated Data) nicht verwendet
            );
            
            if ($decrypted === false) {
                throw new Exception('Entschlüsselung fehlgeschlagen. Falsches Passwort oder manipulierte Daten: ' . openssl_error_string());
            }
            
            return $decrypted;
            
        } catch (Exception $e) {
            throw new Exception('Fehler bei der Entschlüsselung: ' . $e->getMessage());
        }
    }
    
    /**
     * Leitet einen Schlüssel aus einem Passwort und einem Salt ab mittels PBKDF2
     *
     * @param string $password Das Passwort
     * @param string $salt Das Salt
     * @return string Der abgeleitete Schlüssel
     */
    private function deriveKey(string $password, string $salt): string {
        return hash_pbkdf2(
            'sha256',
            $password,
            $salt,
            self::PBKDF2_ITERATIONS,
            self::KEY_LENGTH,
            true
        );
    }
    
    /**
     * Generiert ein sicheres, zufälliges Passwort
     *
     * @param int $length Die Länge des Passworts (Standard: 16)
     * @return string Das generierte Passwort
     * @throws Exception Bei Fehlern in der Zufallsgenerierung
     */
    public function generateSecurePassword(int $length = 16): string {
        // Zeichen, die im Passwort verwendet werden
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()-_=+{};:,<.>/?';
        $charsLength = strlen($chars) - 1;
        
        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $charsLength)];
        }
        
        return $password;
    }
}
