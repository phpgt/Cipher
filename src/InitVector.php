<?php
namespace Gt\Cipher;

use Stringable;

class InitVector implements Stringable {
	private string $bytes;

	public function __construct(
		int $byteLength = 16
	) {
		$this->bytes = random_bytes($byteLength);
	}

	public function getBytes():string {
		return $this->bytes;
	}

	public function __toString():string {
		return bin2hex($this->bytes);
	}
}
