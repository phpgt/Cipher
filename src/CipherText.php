<?php
namespace Gt\Cipher;

use Stringable;

class CipherText implements Stringable {
	private string $bytes;

	public function __construct(
		string $data,
		private InitVector $iv,
		private KeyPair $keyPair,
	) {
		$lockingKeyPair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
			$this->keyPair->getPrivateKey(),
			$this->keyPair->getPublicKey(),
		);
		$this->bytes = sodium_crypto_box(
			$data,
			$this->iv->getBytes(),
			$lockingKeyPair,
		);
	}

	public function __toString():string {
		return base64_encode($this->getBytes());
	}

	public function getBytes():string {
		return $this->bytes;
	}
}
