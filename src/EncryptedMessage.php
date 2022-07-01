<?php
namespace Gt\Cipher;

use Exception;
use Throwable;

class EncryptedMessage extends AbstractMessage {
	public function getMessage():string {
		$encrypted = null;
		if(trim(($this->data), "0..9A..Fa..f") === "") {
			$encrypted = hex2bin($this->data);
		}
		if(!$encrypted) {
			throw new CipherException("Ciphertext does not contain hexadecimal data");
		}

		return (string)openssl_decrypt(
			$encrypted,
			$this->algo,
			$this->privateKey,
			0,
			$this->iv->getBytes(),
		);
	}
}
