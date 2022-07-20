<?php
use Gt\Cipher\KeyPair;
use Gt\Cipher\Message\EncryptedMessage;
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
echo "Cipher: $cipherText", PHP_EOL;
echo "IV: ", $message->getIv(), PHP_EOL;
echo "Sender priv:", $senderKeyPair->getPrivateKey(), PHP_EOL;
echo "Sender pub: ", $senderKeyPair->getPublicKey(), PHP_EOL;
echo "Receiver priv: ", $receiverKeyPair->getPrivateKey(), PHP_EOL;
echo "Receiver pub: ", $receiverKeyPair->getPublicKey(), PHP_EOL;

$encryptedMessage = new EncryptedMessage($cipherText, $message->getIv());
$decrypted = $encryptedMessage->decrypt(
	$receiverKeyPair->getPrivateKey(),
	$senderKeyPair->getPublicKey(),
);

echo "Decrypted: $decrypted", PHP_EOL;
