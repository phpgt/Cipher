<?php
namespace Gt\Cipher;

class Message extends AbstractMessage {
	public function getIv():InitVector {
		return $this->iv;
	}

	public function getCipherText():string {
		$ivBytes = $this->iv->getBytes();
		$requiredIvLength = openssl_cipher_iv_length($this->algo);
		$providedIvLength = strlen($ivBytes);
		if($providedIvLength !== $requiredIvLength) {
			throw new CipherException("$this->algo ciphers require $requiredIvLength bytes, $providedIvLength provided");
		}
		$encrypted = openssl_encrypt(
			$this->data,
			$this->algo,
			$this->privateKey,
			0,
			$ivBytes,
		);
		return bin2hex((string)$encrypted);
	}
}
