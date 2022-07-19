<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\CipherText;
use Gt\Cipher\KeyPair;
use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;

class PlainTextMessage extends AbstractMessage {
	public function encrypt(
		PrivateKey $senderPrivateKey,
		PublicKey $receiverPublicKey,
	):CipherText {
		$lockingKeyPair = new KeyPair(
			$senderPrivateKey,
			$receiverPublicKey,
		);

		return new CipherText(
			$this->data,
			$this->iv,
			$lockingKeyPair,
		);
	}
}
