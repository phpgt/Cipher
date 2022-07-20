<?php
namespace Gt\Cipher;

use Stringable;

class Key implements Stringable {
	protected string $bytes;

	public function __construct(
		?string $bytes = null
	) {
		$this->bytes = $bytes ?? random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
	}

	public function __toString():string {
		return base64_encode($this->bytes);
	}

	public function getBytes():string {
		return $this->bytes;
	}
}
