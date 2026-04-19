<?php
namespace GT\Cipher\Test\Message;

use GT\Cipher\CipherText;
use GT\Cipher\InitVector;
use GT\Cipher\Message\PlainTextMessage;
use GT\Cipher\Key;
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
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));
		$sut = new PlainTextMessage($data, $iv);

		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(str_repeat("1", SODIUM_CRYPTO_SECRETBOX_KEYBYTES));

		self::assertInstanceOf(CipherText::class, $sut->encrypt($key));
	}

	public function testGetIv():void {
		$iv = self::createMock(InitVector::class);
		$sut = new PlainTextMessage("example", $iv);
		self::assertSame($iv, $sut->getIv());
	}
}
