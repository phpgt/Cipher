<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\EncryptedUri;
use Gt\Cipher\InitVector;
use Gt\Cipher\Key;
use Gt\Cipher\Message\DecryptionFailureException;
use Gt\Cipher\MissingQueryStringException;
use PHPUnit\Framework\TestCase;

class EncryptedUriTest extends TestCase {
	public function testConstruct_missingCipher():void {
		self::expectException(MissingQueryStringException::class);
		self::expectExceptionMessage("cipher");
		$uri = "https://example.com/test/?iv=0000";
		new EncryptedUri($uri);
	}

	public function testConstruct_missingIv():void {
		self::expectException(MissingQueryStringException::class);
		self::expectExceptionMessage("iv");
		$uri = "https://example.com/test/?cipher=0000";
		new EncryptedUri($uri);
	}

	public function testConstruct_missingIvMixed():void {
		self::expectException(MissingQueryStringException::class);
		self::expectExceptionMessage("iv");
		$uri = "https://example.com/test/?cipher=0000&vi=1111&ivv=2222&iv[]=3333";
		new EncryptedUri($uri);
	}

	public function testConstruct_missingCipherValue():void {
		self::expectException(MissingQueryStringException::class);
		self::expectExceptionMessage("cipher");
		$uri = "https://example.com/test/?cipher&iv=0000";
		new EncryptedUri($uri);
	}

	public function testDecryptMessage_error():void {
		$ivString = base64_encode(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_NONCEBYTES));
		$uri = "https://example.com/test/?cipher=0000&iv=$ivString";
		$sut = new EncryptedUri($uri);

		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_KEYBYTES));
		self::expectException(DecryptionFailureException::class);
		$sut->decryptMessage($key);
	}

	public function testDecryptMessage():void {
		$uri = "https://example.com/?cipher=lmEClve%2FuhmK32ghM0%2BA%2FI%2Btysm00AL37YD6eg%3D%3D&iv=UVunn3laPVnK4CfHZuS2AnvJ1KfsPM1r";
		$sut = new EncryptedUri($uri);

		$key = self::createMock(Key::class);
		$key->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_SECRETBOX_KEYBYTES));

		$plainTextMessage = $sut->decryptMessage($key);
		self::assertSame("Cipher test!", (string)$plainTextMessage);
	}
}
