<?php
namespace GT\Cipher\Message;

use GT\Cipher\InitVector;
use Stringable;

abstract class AbstractMessage implements Stringable {
	protected InitVector $iv;

	public function __construct(
		protected string $data,
		?InitVector $iv = null,
	) {
		$this->iv = $iv ?? new InitVector();
	}

	public function __toString():string {
		return $this->data;
	}

	public function getIv():InitVector {
		return $this->iv;
	}
}
