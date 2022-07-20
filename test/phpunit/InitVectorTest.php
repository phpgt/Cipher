<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\CipherException;
use Gt\Cipher\InitVector;
use PHPUnit\Framework\TestCase;

class InitVectorTest extends TestCase {
	public function testConstruct_zeroBytes():void {
		self::expectException(CipherException::class);
		self::expectExceptionMessage("IV byte length must be greater than 1");
		new InitVector(0);
	}

	public function testGetBytes():void {
		$sut = new InitVector(16);
		self::assertSame(16, strlen($sut->getBytes()));
	}

	public function testToString_isBase64():void {
		$sut = new InitVector(16);
		$base64 = (string)$sut;
		self::assertNotFalse(base64_decode($base64));
	}

	public function testWithBytes():void {
		$sut = new InitVector();
		$originalBytes = $sut->getBytes();
		$sut2 = $sut->withBytes("changed");
		self::assertSame("changed", $sut2->getBytes());
		self::assertSame($originalBytes, $sut->getBytes());
	}
}
