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
		private Key $key,
	) {

		$this->bytes = sodium_crypto_secretbox(
			$data,
			$this->iv->getBytes(),
			$this->key->getBytes(),
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

		$currentQuery = $baseUri->getQuery();
		parse_str($currentQuery, $queryParams);
		$queryParams["cipher"] = (string)$this;
		$queryParams["iv"] = (string)$this->iv;
		$queryParams["key"] = (string)$this->key;

		return $baseUri->withQuery(http_build_query($queryParams));
	}
}
