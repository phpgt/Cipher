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
		Key $key,
	) {
		if(!$uri instanceof UriInterface) {
			$uri = new Uri($uri);
		}

		parse_str($uri->getQuery(), $queryParams);
		$cipher = $queryParams["cipher"] ?? null;
		$iv = $queryParams["iv"] ?? null;
		$key = $queryParams["key"] ?? null;
		if(!$cipher) {
			throw new MissingQueryStringException("cipher");
		}
		if(!$iv) {
			throw new MissingQueryStringException("iv");
		}
		if(!$key) {
			throw new MissingQueryStringException("key");
		}

		$this->encryptedBytes = base64_decode(str_replace(" ", "+", $cipher));
		$this->iv = (new InitVector())->withBytes(base64_decode(str_replace(" ", "+", $iv)));
		$this->key = new Key(base64_decode(str_replace(" ", "+", $key)));
	}

	public function decryptMessage():PlainTextMessage {
		$decrypted = sodium_crypto_secretbox_open(
			$this->encryptedBytes,
			$this->iv->getBytes(),
			$this->key->getBytes(),
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
