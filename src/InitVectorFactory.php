<?php
namespace Gt\Cipher;

class InitVectorFactory {
	public function fromString(string $hexString):InitVector {
		$bytes = hex2bin($hexString);
		return (new InitVector())->withBytes($bytes);
	}
}
