<?php
use GT\Cipher\EncryptedUri;
use GT\Cipher\Key;
use GT\Cipher\Message\PlainTextMessage;

require(__DIR__ . "/../vendor/autoload.php");

$sharedKey = new Key();
$message = new PlainTextMessage("Meet at the agreed location");
$cipherText = $message->encrypt($sharedKey);

$encryptedUri = $cipherText->getUri("https://example.com/receiver.php");
echo "Encrypted URI: ", $encryptedUri, PHP_EOL;

// Simulate the receiving application reading the incoming request URI.
$receivedUri = new EncryptedUri((string)$encryptedUri);
$decryptedMessage = $receivedUri->decryptMessage($sharedKey);

echo "Decrypted message: ", $decryptedMessage, PHP_EOL;
