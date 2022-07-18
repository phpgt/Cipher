<?php
namespace Gt\Cipher;

class KeyPair {
	public function __construct(
		private ?PrivateKey $privateKey = null,
		private ?PublicKey $publicKey = null,
	) {
		$this->privateKey = $this->privateKey ?? new PrivateKey();
		$this->publicKey = $this->publicKey ?? new PublicKey();
	}

	public function getPrivateKey():PrivateKey {
		return $this->privateKey;
	}

	public function getPublicKey():PublicKey {
		return $this->publicKey;
	}
}
