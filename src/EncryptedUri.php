<?php
namespace Gt\Cipher;

use Gt\Cipher\Message\DecryptionFailureException;
use Gt\Cipher\Message\PlainTextMessage;
use Gt\Http\Uri;
use Psr\Http\Message\UriInterface;

class EncryptedUri {
	private string $encryptedBytes;
	private InitVector $iv;
	private Key $key;

	public function __construct(
		string|UriInterface $uri,
	) {
		if(!$uri instanceof UriInterface) {
			$uri = new Uri($uri);
		}

		parse_str($uri->getQuery(), $queryParams);
		$cipher = $queryParams["cipher"] ?? null;
		$iv = $queryParams["iv"] ?? null;
		if(!$cipher || !is_string($cipher)) {
			throw new MissingQueryStringException("cipher");
		}
		if(!$iv || !is_string($iv)) {
			throw new MissingQueryStringException("iv");
		}

		$this->encryptedBytes = base64_decode(str_replace(" ", "+", $cipher));
		$this->iv = (new InitVector())->withBytes(base64_decode(str_replace(" ", "+", $iv)));
	}

	public function decryptMessage(Key $key):PlainTextMessage {
		$decrypted = sodium_crypto_secretbox_open(
			$this->encryptedBytes,
			$this->iv->getBytes(),
			$key->getBytes(),
		);
		if($decrypted === false) {
			throw new DecryptionFailureException("Error decrypting cipher message");
		}
		return new PlainTextMessage(
			$decrypted,
			$this->iv,
		);
	}
}
