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
			$receiverPrivateKey,
			$senderPublicKey,
		);
		$decrypted = sodium_crypto_box_open(
			hex2bin($this->data),
			$this->iv->getBytes(),
			$unlockingKeyPair,
		);
		return new PlainTextMessage(
			$decrypted,
			$this->iv,
		);
	}
}
