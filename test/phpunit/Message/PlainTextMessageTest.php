<?php
namespace Gt\Cipher\Test\Message;

use Gt\Cipher\InitVector;
use Gt\Cipher\KeyPair;
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
}
