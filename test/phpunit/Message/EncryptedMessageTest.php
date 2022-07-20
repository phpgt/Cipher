<?php
namespace Gt\Cipher\Test\Message;

use Gt\Cipher\InitVector;
use Gt\Cipher\Message\DecryptionFailureException;
use Gt\Cipher\Message\EncryptedMessage;
use Gt\Cipher\Key;
use PHPUnit\Framework\TestCase;

class EncryptedMessageTest extends TestCase {
	public function testToString():void {
		$sut = new EncryptedMessage("0000");
		self::assertSame("0000", (string)$sut);
	}

	/** @noinspection SpellCheckingInspection */
	public function testDecrypt():void {
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(base64_decode("8Zf3DaE343vn3LLDiyTlFCS6iFP4RVMw"));
		$sut = new EncryptedMessage("j+MfN+Uomaqh4iGj0I3Ng8cy+rWwW/L2ntiB5w==", $iv);

		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(base64_decode("a66WIhWzyCht2q7Y54A5UdNbKrnCIJrAoS1ov/QFg7k="));

		$decrypted = $sut->decrypt($key);
		self::assertSame("Cipher test!", (string)$decrypted);
	}

	public function testDecrypt_failure():void {
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));
		$sut = new EncryptedMessage("badly formed data", $iv);

		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_KEYBYTES));

		self::expectException(DecryptionFailureException::class);
		$sut->decrypt($key);
	}
}
