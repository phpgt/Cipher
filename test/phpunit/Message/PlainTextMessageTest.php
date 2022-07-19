<?php
namespace Gt\Cipher\Test\Message;

use Gt\Cipher\CipherText;
use Gt\Cipher\InitVector;
use Gt\Cipher\Message\PlainTextMessage;
use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;
use PHPUnit\Framework\TestCase;

class PlainTextMessageTest extends TestCase {
	public function testToString():void {
		$data = "Test message";

		$sut = new PlainTextMessage($data);
		self::assertSame($data, (string)$sut);
	}

	public function testEncrypt():void {
		$data = "Test message";

		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_NONCEBYTES));
		$sut = new PlainTextMessage($data, $iv);

		$senderPrivateKey = self::createMock(PrivateKey::class);
		$senderPrivateKey->method("__toString")
			->willReturn(str_repeat("1", SODIUM_CRYPTO_BOX_SECRETKEYBYTES));
		$receiverPublicKey = self::createMock(PublicKey::class);
		$receiverPublicKey->method("__toString")
			->willReturn(str_repeat("2", SODIUM_CRYPTO_BOX_PUBLICKEYBYTES));

		self::assertInstanceOf(CipherText::class, $sut->encrypt(
			$senderPrivateKey,
			$receiverPublicKey,
		));
	}

	public function testGetIv():void {
		$iv = self::createMock(InitVector::class);
		$sut = new PlainTextMessage("example", $iv);
		self::assertSame($iv, $sut->getIv());
	}
}
