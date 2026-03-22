<?php
use Gt\Cipher\EncryptionFailureException;
use Gt\Cipher\Message\DecryptionFailureException;

require("vendor/autoload.php");

$messageToTransmit = "Cipher test!";
$sharedKey = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
$iv = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

try {
	$encryptedBytes = sodium_crypto_secretbox(
		$messageToTransmit,
		$iv,
		$sharedKey
	);
}
catch(Throwable $throwable) {
	throw new EncryptionFailureException(
		"Error encrypting cipher message",
		previous: $throwable,
	);
}
$cipher = base64_encode($encryptedBytes);

echo "Shared key: ", base64_encode($sharedKey), PHP_EOL;
echo "IV: ", base64_encode($iv), PHP_EOL;
echo "Cipher: ", base64_encode($cipher), PHP_EOL;

try {
	$decrypted = sodium_crypto_secretbox_open(
		$encryptedBytes,
		$iv,
		$sharedKey,
	);
}
catch(Throwable $throwable) {
	throw new DecryptionFailureException(
		"Error decrypting cipher message",
		previous: $throwable,
	);
}

if($decrypted === false) {
	throw new DecryptionFailureException("Error decrypting cipher message");
}

echo "Decrypted: $decrypted", PHP_EOL;
