<?php
namespace Gt\Cipher;

use Gt\Http\Uri;
use Psr\Http\Message\UriInterface;
use Stringable;

class CipherText implements Stringable {
	private string $bytes;

	public function __construct(
		string $data,
		private InitVector $iv,
		private KeyPair $keyPair,
	) {
		$lockingKeyPair = sodium_crypto_box_keypair_from_secretkey_and_publickey(
			$this->keyPair->getPrivateKey()->getBytes(),
			$this->keyPair->getPublicKey()->getBytes(),
		);
		$this->bytes = sodium_crypto_box(
			$data,
			$this->iv->getBytes(),
			$lockingKeyPair,
		);
	}

	public function __toString():string {
		return base64_encode($this->getBytes());
	}

	public function getBytes():string {
		return $this->bytes;
	}

	public function getUri(string|UriInterface $baseUri = ""):UriInterface {
		if(!$baseUri instanceof UriInterface) {
			$baseUri = new Uri($baseUri);
		}

		return $baseUri
			->withQueryValue("cipher", (string)$this)
			->withQueryValue("iv", (string)$this->iv)
			->withQueryValue("key", (string)$this->keyPair->getPrivateKey()->getMatchingPublicKey());
	}
}
