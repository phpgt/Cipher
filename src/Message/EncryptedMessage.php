<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;

class EncryptedMessage extends AbstractMessage {
	public function decrypt(
		PrivateKey $receiverPrivateKey,
		PublicKey $senderPublicKey,
	):PlainTextMessage {
		$errorMessage = "Error decrypting cipher message";
		$decrypted = false;

		try {
			$unlockingKeyPair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
				$receiverPrivateKey->getBytes(),
				$senderPublicKey->getBytes(),
			);
			$decrypted = sodium_crypto_box_open(
				base64_decode($this->data),
				$this->iv->getBytes(),
				$unlockingKeyPair,
			);
		}
		catch(\SodiumException $exception) {
// TODO: Issue #10 - this is one of the exceptions, but I think it makes sense to parse the message and extract the meaningful information.
// Friendly exception messages are the future!
			$errorMessage = $exception->getMessage();
		}
		if($decrypted === false) {
			throw new DecryptionFailureException($errorMessage);
		}
		return new PlainTextMessage(
			$decrypted,
			$this->iv,
		);
	}
}
