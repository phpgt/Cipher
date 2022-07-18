<?php
namespace Gt\Cipher;

use Psr\Http\Message\UriInterface;
use Stringable;
use Gt\Http\Uri;

class UriAdapter implements Stringable {
	const KEY_CIPHER = "cipher";
	const KEY_IV = "iv";

	private UriInterface $uri;

	public function __construct(
		private PlainTextMessage $message,
		null|string|UriInterface $uri = null,
	) {
		if($uri instanceof UriInterface) {
			$this->uri = $uri;
		}
		else {
			$this->uri = new Uri($uri);
		}
	}

	public function __toString():string {
		return (string)$this->getUri();
	}

	public function getUri():UriInterface {
		$uri = new Uri((string)$this->uri);
		foreach($this->getNewQueryStringParts() as $key => $value) {
			$uri = $uri->withQueryValue($key, $value);
		}

		return $uri;
	}

	public function getQueryString():string {
		return http_build_query($this->getQueryStringParts());
	}

	/** @return array<string,string> */
	public function getQueryStringParts():array {
		$newParts = $this->getNewQueryStringParts();
		parse_str($this->uri->getQuery(), $existingParts);
		return array_merge($existingParts, $newParts);
	}

	/** @return array<string,string> */
	protected function getNewQueryStringParts():array {
		return [
			self::KEY_IV => (string)$this->message->getIv(),
			self::KEY_CIPHER => $this->message->getCipherText(),
		];
	}
}
