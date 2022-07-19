<?php
namespace Gt\Cipher;

use Stringable;

class Key implements Stringable {
	public function __construct(
		private string $binaryData
	) {}

	public function __toString():string {
		return $this->binaryData;
	}
}
