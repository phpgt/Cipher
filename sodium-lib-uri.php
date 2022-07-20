<?php
use Gt\Cipher\EncryptedUri;
use Gt\Cipher\Key;
use Gt\Cipher\Message\PlainTextMessage;

require("vendor/autoload.php");

$sharedKey = new Key();
$message = new PlainTextMessage("This message will be sent from sender to receiver, via Sodium!");
echo "Message to send: $message", PHP_EOL;

$cipherText = $message->encrypt($sharedKey);
$uri = $cipherText->getUri("https://example.com/");
echo "URI: $uri", PHP_EOL;

// At this point, the remote code at example.com has access to the encrypted
// message from the URI's query string parameters.

// The following code represents the receiving side of the platform:
$incomingUri = (string)$uri;
$encryptedUri = new EncryptedUri($uri);
$plainTextMessage = $encryptedUri->decryptMessage();

echo "Decrypted: $plainTextMessage", PHP_EOL;
