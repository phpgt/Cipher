<?php
use GT\Cipher\Key;
use GT\Cipher\Message\EncryptedMessage;
use GT\Cipher\Message\PlainTextMessage;

require(__DIR__ . "/../vendor/autoload.php");

$sharedKey = new Key();
$message = new PlainTextMessage("Hello from PHP.GT Cipher");

echo "Original message: ", $message, PHP_EOL;
echo "Shared key: ", $sharedKey, PHP_EOL;
echo "IV: ", $message->getIv(), PHP_EOL;

$cipherText = $message->encrypt($sharedKey);
echo "Cipher text: ", $cipherText, PHP_EOL;

$receivedMessage = new EncryptedMessage($cipherText, $message->getIv());
$decryptedMessage = $receivedMessage->decrypt($sharedKey);

echo "Decrypted message: ", $decryptedMessage, PHP_EOL;
