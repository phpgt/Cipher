<?php
use Gt\Cipher\EncryptedUri;
use Gt\Cipher\Key;
use Gt\Cipher\Message\PlainTextMessage;

require("vendor/autoload.php");

$sharedKey = new Key();
$message = new PlainTextMessage("Cipher test!");
echo "Message to send: $message", PHP_EOL;

$cipherText = $message->encrypt($sharedKey);
$uri = $cipherText->getUri("https://example.com/");
echo "Key: $sharedKey", PHP_EOL;
echo "URI: $uri", PHP_EOL;

// At this point, the remote code at example.com has access to the encrypted
// message from the URI's query string parameters.

// The following code represents the receiving side of the platform:
$incomingUri = (string)$uri;
$encryptedUri = new EncryptedUri($uri);
$plainTextMessage = $encryptedUri->decryptMessage($sharedKey);

echo "Decrypted: $plainTextMessage", PHP_EOL;
