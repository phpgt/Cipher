<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\PublicKey;
use Gt\Cipher\InitVector;
use Gt\Cipher\KeyPair;
use Gt\Cipher\PlainTextMessage;
use PHPUnit\Framework\TestCase;

class PlainTextMessageTest extends TestCase {
	public function testToString():void {
		$data = "Test message";

		$keyPair = self::createMock(KeyPair::class);
		$iv = self::createMock(InitVector::class);
		$sut = new PlainTextMessage($data, $keyPair, $iv);
		self::assertSame($data, (string)$sut);
	}

	public function testGetCipherText():void {
		$data = "Test message";

		$keyPair = self::createMock(KeyPair::class);
		$iv = self::createMock(InitVector::class);
		$sut = new PlainTextMessage($data, $keyPair, $iv);

		$receiverKey = self::createMock(PublicKey::class);
		$cipher = $sut->getCipherText($receiverKey);
	}
}
