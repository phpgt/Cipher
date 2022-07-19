<?php
namespace Gt\Cipher;

use Stringable;

abstract class Key implements Stringable {
	protected string $bytes;

	public function __construct(
		?string $bytes = null
	) {
		$this->bytes = $bytes ?? $this->generateRandomBytes();
	}

	abstract protected function generateRandomBytes():string;

	public function __toString():string {
		return base64_encode($this->bytes);
	}

	public function getBytes():string {
		return $this->bytes;
	}
}
