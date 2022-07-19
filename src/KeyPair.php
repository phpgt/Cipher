<?php
namespace Gt\Cipher;

use Stringable;

class KeyPair implements Stringable {
	private PrivateKey $privateKey;
	private PublicKey $publicKey;

	public function __construct(
		?PrivateKey $privateKey = null,
		?PublicKey $publicKey = null,
	) {
		if($privateKey && !$publicKey) {
			// TODO: Either both or neither must be provided!
			// Throw exception.
		}
		$sodiumKeyPair = "";
		if(!$privateKey && !$publicKey) {
			$sodiumKeyPair = sodium_crypto_box_keypair();
		}

		$this->privateKey = $privateKey ?? new PrivateKey(sodium_crypto_box_secretkey($sodiumKeyPair));
		$this->publicKey = $publicKey ?? new PublicKey(sodium_crypto_box_publickey($sodiumKeyPair));
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
