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

	public function testToString_isHex():void {
		$sut = new InitVector(16);
		$hex = (string)$sut;
		self::assertMatchesRegularExpression("/[0-9a-f]{32}/", $hex);
	}
}
