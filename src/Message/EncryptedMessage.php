<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\Key;
use Throwable;

class EncryptedMessage extends AbstractMessage {
	public function decrypt(Key $key):PlainTextMessage {
		try {
			$decrypted = sodium_crypto_secretbox_open(
				base64_decode($this->data),
				$this->iv->getBytes(),
				$key->getBytes(),
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
		return new PlainTextMessage(
			$decrypted,
			$this->iv,
		);
	}
}
