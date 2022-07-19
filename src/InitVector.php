<?php
namespace Gt\Cipher;

use Stringable;

class InitVector implements Stringable {
	private string $bytes;

	public function __construct(
		int $byteLength = SODIUM_CRYPTO_BOX_NONCEBYTES
	) {
		if($byteLength < 1) {
			throw new CipherException("IV byte length must be greater than 1");
		}
		$this->bytes = random_bytes($byteLength);
	}

	public function getBytes():string {
		return $this->bytes;
	}

	public function withBytes(string $bytes):self {
		$clone = clone $this;
		$clone->bytes = $bytes;
		return $clone;
	}

	public function __toString():string {
		return bin2hex($this->bytes);
	}
}
