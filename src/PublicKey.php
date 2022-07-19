<?php
namespace Gt\Cipher;

class PublicKey extends Key {
	protected function generateRandomBytes():string {
		return random_bytes(SODIUM_CRYPTO_BOX_PUBLICKEYBYTES);
	}
}
