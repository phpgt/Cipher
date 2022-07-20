<?php
$messageToTransmit = "This message will be sent from sender to receiver, via Sodium!";
$sharedKey = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
$iv = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

$encryptedBytes = sodium_crypto_secretbox(
	$messageToTransmit,
	$iv,
	$sharedKey
);
$cipher = base64_encode($encryptedBytes);

echo "Shared key: ", base64_encode($sharedKey), PHP_EOL;
echo "IV: ", base64_encode($iv), PHP_EOL;
echo "Cipher: ", base64_encode($cipher), PHP_EOL;

$decrypted = sodium_crypto_secretbox_open(
	$encryptedBytes,
	$iv,
	$sharedKey,
);

echo "Decrypted: $decrypted", PHP_EOL;
