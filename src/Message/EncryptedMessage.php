<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\Key;

class EncryptedMessage extends AbstractMessage {
	public function decrypt(Key $key):PlainTextMessage {
		$decrypted = sodium_crypto_secretbox_open(
			base64_decode($this->data),
			$this->iv->getBytes(),
			$key->getBytes(),
		);
		if($decrypted === false) {
			throw new DecryptionFailureException("Error decrypting cipher message");
		}
		return new PlainTextMessage(
			$decrypted,
			$this->iv,
		);
	}
}
