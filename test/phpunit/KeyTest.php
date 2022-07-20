<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\Key;
use PHPUnit\Framework\TestCase;

class KeyTest extends TestCase {
	public function testToString():void {
		$len = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;
		$zeroBytes = str_repeat("0", $len);
		$expectedValue = base64_encode($zeroBytes);
		$sut = new Key($zeroBytes);
		self::assertSame($expectedValue, (string)$sut);
	}

	public function testToString_defaultRandom():void {
		$len = SODIUM_CRYPTO_SECRETBOX_KEYBYTES;
		$zeroBytes = str_repeat("0", $len);
		$expectedValue = base64_encode($zeroBytes);
		$sut = new Key();
		self::assertNotSame($expectedValue, (string)$sut);
		self::assertSame($len, strlen(base64_decode($sut)));
	}

	public function testGetBytes():void {
		$bytes = random_bytes(SODIUM_CRYPTO_SECRETBOX_KEYBYTES);
		$sut = new Key($bytes);
		self::assertSame($bytes, $sut->GetBytes());
	}
}
