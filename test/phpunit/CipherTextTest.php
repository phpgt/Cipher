<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\CipherText;
use Gt\Cipher\InitVector;
use Gt\Cipher\KeyPair;
use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;
use PHPUnit\Framework\TestCase;

class CipherTextTest extends TestCase {
	public function testToString():void {
// This is the known output when using all zero bytes for the encryption
// process. The mocked classes all return zero-byte strings for testing.
		$encryptedDataFromZeroBytes = "nvwP8aCIi9PPvCSiZP9o9gEPFDcSisOaxA==";

		$data = "test data";
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_NONCEBYTES));
		$keyPair = self::createMock(KeyPair::class);
		$privateKey = self::createMock(PrivateKey::class);
		$privateKey->method("__toString")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_SECRETKEYBYTES));
		$keyPair->method("getPrivateKey")
			->willReturn($privateKey);
		$publicKey = self::createMock(PublicKey::class);
		$publicKey->method("__toString")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_PUBLICKEYBYTES));
		$keyPair->method("getPublicKey")
			->willReturn($publicKey);

		$sut = new CipherText(
			$data,
			$iv,
			$keyPair,
		);
		self::assertSame($encryptedDataFromZeroBytes, (string)$sut);
	}
}
