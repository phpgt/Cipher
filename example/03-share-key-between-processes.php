<?php
use GT\Cipher\Key;
use GT\Cipher\Message\EncryptedMessage;
use GT\Cipher\Message\PlainTextMessage;

require(__DIR__ . "/../vendor/autoload.php");

// In a real system, both applications would already know the same secret key.
$senderKey = new Key();
$sharedKeyString = (string)$senderKey;
$receiverKey = new Key(base64_decode($sharedKeyString));

$message = new PlainTextMessage("Only the holder of the same key can read this.");
$cipherText = $message->encrypt($senderKey);

echo "Share this key securely: ", $sharedKeyString, PHP_EOL;
echo "Cipher text to transmit: ", $cipherText, PHP_EOL;
echo "IV to transmit: ", $message->getIv(), PHP_EOL;

$receivedMessage = new EncryptedMessage($cipherText, $message->getIv());
$decryptedMessage = $receivedMessage->decrypt($receiverKey);

echo "Decrypted on the receiving side: ", $decryptedMessage, PHP_EOL;
