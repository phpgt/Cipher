<?php
namespace Gt\Cipher;

class PlainTextMessage extends AbstractMessage {
	public function __toString():string {
		return $this->data;
	}

	public function getCipherText(PublicKey $receiverKey):CipherText {
		$lockingKeyPair = new KeyPair(
			$this->keyPair->getPrivateKey(),
			$receiverKey
		);

//		$encryptedBytes = sodium_crypto_box(
//			$this->data,
//			$this->iv->getBytes(),
//			$this->keyPair,
//		);
		return new CipherText(
			$this->data,
			$this->iv,
			$lockingKeyPair,
		);
	}
}
