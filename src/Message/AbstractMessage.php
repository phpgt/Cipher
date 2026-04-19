<?php
namespace GT\Cipher\Message;

use GT\Cipher\CipherException;
use GT\Cipher\InitVector;
use Stringable;

abstract class AbstractMessage implements Stringable {
	protected InitVector $iv;

	public function __construct(
		protected string $data,
		null|string|InitVector $iv = null,
	) {
		if(is_string($iv)) {
			$decodedIv = base64_decode($iv, true);
			if($decodedIv === false) {
				throw new CipherException("IV must be a valid base64 string");
			}

			$iv = (new InitVector())->withBytes($decodedIv);
		}

		$this->iv = $iv ?? new InitVector();
	}

	public function __toString():string {
		return $this->data;
	}

	public function getIv():InitVector {
		return $this->iv;
	}
}
