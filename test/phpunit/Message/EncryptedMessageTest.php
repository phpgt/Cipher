<?php
namespace Gt\Cipher\Test\Message;

use Gt\Cipher\InitVector;
use Gt\Cipher\Message\DecryptionFailureException;
use Gt\Cipher\Message\EncryptedMessage;
use Gt\Cipher\PrivateKey;
use Gt\Cipher\PublicKey;
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
			->willReturn(base64_decode("M7BxcNSaAQ2YUx3LNXynD+pwtgE/WTrn"));
		$sut = new EncryptedMessage("mOdLvLnBDcmzrSNRl8svamDCXdJMee8znuuZ4A==", $iv);

		$receiverPrivateKey = self::createMock(PrivateKey::class);
		$receiverPrivateKey->method("getBytes")
			->willReturn(base64_decode("3K0XYSF2Y9m/AuStDHWVi6EJql1UT6u3rIJj4L3tj1o="));
		$senderPublicKey = self::createMock(PublicKey::class);
		$senderPublicKey->method("getBytes")
			->willReturn(base64_decode("F63muPVYXtqNKO/82FePpi5YD2IzU3bh8qwOcgeWimU="));

		$decrypted = $sut->decrypt($receiverPrivateKey, $senderPublicKey);
		self::assertSame("Cipher test!", (string)$decrypted);
	}

	public function testDecrypt_failure():void {
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_NONCEBYTES));
		$sut = new EncryptedMessage("badly formed data", $iv);

		$receiverPrivateKey = self::createMock(PrivateKey::class);
		$receiverPrivateKey->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_SECRETKEYBYTES));
		$senderPublicKey = self::createMock(PublicKey::class);
		$senderPublicKey->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_PUBLICKEYBYTES));

		self::expectException(DecryptionFailureException::class);
		$sut->decrypt(
			$receiverPrivateKey,
			$senderPublicKey,
		);
	}

	public function testDecrypt_incorrectKeySize():void {
		$iv = self::createMock(InitVector::class);
		$iv->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_NONCEBYTES - 2));
		$sut = new EncryptedMessage("badly formed data", $iv);

		$receiverPrivateKey = self::createMock(PrivateKey::class);
		$receiverPrivateKey->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_SECRETKEYBYTES));
		$senderPublicKey = self::createMock(PublicKey::class);
		$senderPublicKey->method("getBytes")
			->willReturn(str_repeat("0", SODIUM_CRYPTO_BOX_PUBLICKEYBYTES));

		self::expectExceptionMessage("sodium_crypto_box_open(): Argument #2 (\$nonce) must be SODIUM_CRYPTO_BOX_NONCEBYTES bytes long");
		self::expectException(DecryptionFailureException::class);
		$sut->decrypt(
			$receiverPrivateKey,
			$senderPublicKey,
		);
	}
}
