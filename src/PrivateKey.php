<?php
namespace Gt\Cipher;

class PrivateKey extends Key {
	public function getMatchingPublicKey():PublicKey {
		return new PublicKey(
			sodium_crypto_box_publickey_from_secretkey($this->getBytes())
		);
	}
}
