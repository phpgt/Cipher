<?php
use Gt\Cipher\Key;
use Gt\Cipher\Message\EncryptedMessage;
use Gt\Cipher\Message\PlainTextMessage;

require("vendor/autoload.php");

$sharedKey = new Key();
$message = new PlainTextMessage("Cipher test!");
echo "Message to send: $message", PHP_EOL;

$cipherText = $message->encrypt($sharedKey);

echo "Shared key: $sharedKey", PHP_EOL;
echo "IV: ", $message->getIv(), PHP_EOL;
echo "Cipher: $cipherText", PHP_EOL;

$encryptedMessage = new EncryptedMessage($cipherText, $message->getIv());
$decrypted = $encryptedMessage->decrypt($sharedKey);

echo "Decrypted: $decrypted", PHP_EOL;
