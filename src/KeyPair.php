<?php
namespace Gt\Cipher;

use Stringable;

class KeyPair implements Stringable {
	public function __construct(
		private ?PrivateKey $privateKey = null,
		private ?PublicKey $publicKey = null,
	) {
		if($this->privateKey && !$this->publicKey) {
			// TODO: Either both or neither must be provided!
			// Throw exception.
		}
		if(!$this->privateKey && !$this->publicKey) {
			$sodiumKeyPair = sodium_crypto_box_keypair();
			$this->privateKey = new PrivateKey(sodium_crypto_box_secretkey($sodiumKeyPair));
			$this->publicKey = new PublicKey(sodium_crypto_box_publickey($sodiumKeyPair));
		}
	}

	public function __toString():string {
		return sodium_crypto_box_keypair_from_secretkey_and_publickey(
			$this->privateKey,
			$this->publicKey,
		);
	}

	public function getPrivateKey():PrivateKey {
		return $this->privateKey;
	}

	public function getPublicKey():PublicKey {
		return $this->publicKey;
	}
}
