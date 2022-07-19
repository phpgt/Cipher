<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;

class EncryptedMessage extends AbstractMessage {
	public function decrypt(
		PrivateKey $receiverPrivateKey,
		PublicKey $senderPublicKey,
	):PlainTextMessage {
		$unlockingKeyPair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
			$receiverPrivateKey->getBytes(),
			$senderPublicKey->getBytes(),
		);
		$decrypted = sodium_crypto_box_open(
			base64_decode($this->data),
			$this->iv->getBytes(),
			$unlockingKeyPair,
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
