<?php
$messageToTransmit = "This message will be sent to from sender to receiver, via Sodium!";

$senderKeyPair = sodium_crypto_box_keypair();
$senderPublicKey = sodium_crypto_box_publickey($senderKeyPair);
$senderPrivateKey = sodium_crypto_box_secretkey($senderKeyPair);

$receiverKeyPair = sodium_crypto_box_keypair();
$receiverPublicKey = sodium_crypto_box_publickey($receiverKeyPair);
$receiverPrivateKey = sodium_crypto_box_secretkey($receiverKeyPair);

$iv = random_bytes(SODIUM_CRYPTO_BOX_NONCEBYTES);

$lockingKeyPair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
	$senderPrivateKey,
	$receiverPublicKey
);
$encryptedBytes = sodium_crypto_box($messageToTransmit, $iv, $lockingKeyPair);
$cipher = base64_encode($encryptedBytes);

echo $cipher, PHP_EOL;

$unlockingKeyPair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
	$receiverPrivateKey,
	$senderPublicKey,
);
$decrypted = sodium_crypto_box_open(
	$encryptedBytes,
	$iv,
	$unlockingKeyPair
);

echo $decrypted, PHP_EOL;
