<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\InitVector;
use Gt\Cipher\Message;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase {
	public function testGetIv_constructWithIv():void {
		$iv = self::createMock(InitVector::class);
		$sut = new Message("message", "private key", $iv);
		self::assertSame($iv, $sut->getIv());
	}

	public function testGetIv_noConstructWithIv():void {
		$sut = new Message("message", "private key");
		self::assertInstanceOf(InitVector::class, $sut->getIv());
	}

	public function testGetCipherText():void {
		$message = "Hello, PHP.Gt!";
		$privateKey = bin2hex(random_bytes(32));

		$iv = self::createMock(InitVector::class);
		$iv->expects(self::once())
			->method("getBytes")
			->willReturn("1111222233334444");

		$expectedEncrypted = openssl_encrypt(
			$message,
			"aes-256-ctr",
			$privateKey,
			0,
			"1111222233334444",
		);

		$sut = new Message($message, $privateKey, $iv);
		$cipherText = $sut->getCipherText();
		self::assertSame(bin2hex($expectedEncrypted), $cipherText);
	}
}
