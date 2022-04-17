<?php
namespace Gt\Cipher;

use Stringable;

class InitVector implements Stringable {
	private string $bytes;

	public function __construct(
		int $byteLength = 16
	) {
		if($byteLength < 1) {
			throw new CipherException("IV byte length must be positive");
		}
		$this->bytes = random_bytes($byteLength);
	}

	public function getBytes():string {
		return $this->bytes;
	}

	public function __toString():string {
		return bin2hex($this->bytes);
	}
}
