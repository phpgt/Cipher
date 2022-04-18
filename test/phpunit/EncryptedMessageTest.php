<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\AbstractMessage;
use Gt\Cipher\CipherException;
use Gt\Cipher\EncryptedMessage;
use Gt\Cipher\InitVector;
use PHPUnit\Framework\TestCase;

class EncryptedMessageTest extends TestCase {
	public function testGetMessage_invalidCipher():void {
		$iv = self::createMock(InitVector::class);
		self::expectException(CipherException::class);
		self::expectExceptionMessage("Ciphertext does not contain hexadecimal data");
		$sut = new EncryptedMessage("this is not a cipher", $iv);
		$sut->getMessage();
	}

	public function testGetMessage():void {
		$message = "Hello, PHP.Gt!";
		$privateKey = random_bytes(32);

		$ivBytes = "1111222233334444";
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn($ivBytes);

		$encrypted = openssl_encrypt(
			$message,
			AbstractMessage::DEFAULT_ALGO,
			$privateKey,
			0,
			$iv->getBytes()
		);
		$cipherText = bin2hex($encrypted);
		$sut = new EncryptedMessage($cipherText, $privateKey, $iv);
		self::assertSame($message, $sut->getMessage());
	}
}
