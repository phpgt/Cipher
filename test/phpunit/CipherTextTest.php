<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\CipherText;
use Gt\Cipher\InitVector;
use Gt\Cipher\Key;
use PHPUnit\Framework\TestCase;

class CipherTextTest extends TestCase {
	public function testToString():void {
// This is the known output when using all zero bytes for the encryption
// process. The mocked classes all return zero-byte strings for testing.
		$encryptedDataFromZeroBytes = "1Mgu+MZ9RWvImPE+cNnQeRI9iMKH+cv63A==";

		$data = "test data";
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));
		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_KEYBYTES));

		$sut = new CipherText(
			$data,
			$iv,
			$key,
		);
		self::assertSame($encryptedDataFromZeroBytes, (string)$sut);
	}

	public function testGetUri():void {
		$data = "Example message";
		$baseUri = "https://example.com";

		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));
		$iv->method("__toString")
			->willReturn(base64_encode($iv->getBytes()));
		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
		$sut = new CipherText($data, $iv, $key);

		$expectedQueryParts = [
			"cipher" => base64_encode($sut->getBytes()),
			"iv" => base64_encode($iv->getBytes()),
		];

		$uri = $sut->getUri($baseUri);
		self::assertSame($baseUri, $uri->getScheme() . "://" . $uri->getAuthority());
		self::assertSame(http_build_query($expectedQueryParts), $uri->getQuery());
	}
}
