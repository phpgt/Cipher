<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\KeyPair;
use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;
use PHPUnit\Framework\TestCase;

class KeyPairTest extends TestCase {
	public function testGetPrivateKey():void {
		$privateKey = self::createMock(PrivateKey::class);
		$publicKey = self::createMock(PublicKey::class);
		$sut = new KeyPair($privateKey, $publicKey);
		self::assertSame($privateKey, $sut->getPrivateKey());
	}

	public function testGetPublicKey():void {
		$privateKey = self::createMock(PrivateKey::class);
		$publicKey = self::createMock(PublicKey::class);
		$sut = new KeyPair($privateKey, $publicKey);
		self::assertSame($publicKey, $sut->getPublicKey());
	}

	public function testToString():void {
		$expectedByteString = "AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABBBBBBBBBBBBBBBBBBBBBBBBBBBBBBBB";
		$privateKey = self::createMock(PrivateKey::class);
		$privateKey->method("__toString")
			->willReturn(str_repeat("A", SODIUM_CRYPTO_BOX_SECRETKEYBYTES));
		$publicKey = self::createMock(PublicKey::class);
		$publicKey->method("__toString")
			->willReturn(str_repeat("B", SODIUM_CRYPTO_BOX_PUBLICKEYBYTES));
		$sut = new KeyPair($privateKey, $publicKey);
		self::assertSame($expectedByteString, (string)$sut);
	}

	public function testConstruct_defaultParameters():void {
		$sut = new KeyPair();
		self::assertSame(SODIUM_CRYPTO_BOX_SECRETKEYBYTES, strlen($sut->getPrivateKey()));
		self::assertSame(SODIUM_CRYPTO_BOX_PUBLICKEYBYTES, strlen($sut->getPublicKey()));
	}
}
