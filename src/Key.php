<?php
namespace Gt\Cipher;

use Stringable;

class Key implements Stringable {
	public function __construct(
		private string $bytes
	) {}

	public function __toString():string {
		return base64_encode($this->bytes);
	}

	public function getBytes():string {
		return $this->bytes;
	}
}
