<?php
use Gt\Cipher\EncryptedUri;
use Gt\Cipher\KeyPair;
use Gt\Cipher\Message\PlainTextMessage;

require("vendor/autoload.php");

$senderKeyPair = new KeyPair();
$receiverKeyPair = new KeyPair();

$message = new PlainTextMessage("This message will be sent from sender to receiver, via Sodium!");
echo "Message to send: $message", PHP_EOL;

$cipherText = $message->encrypt(
	$senderKeyPair->getPrivateKey(),
	$receiverKeyPair->getPublicKey(),
);
$uri = $cipherText->getUri("https://example.com/");
echo "URI: $uri", PHP_EOL;

// At this point, the remote code at example.com has access to the encrypted
// message from the URI's query string parameters.

// The following code represents the receiving side of the platform:
$incomingUri = (string)$uri;
$encryptedUri = new EncryptedUri(
	$uri,
	$receiverKeyPair->getPrivateKey(),
);
$plainTextMessage = $encryptedUri->decryptMessage();

echo "Decrypted: $plainTextMessage", PHP_EOL;
