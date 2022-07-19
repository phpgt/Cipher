<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\InitVector;
use Gt\Cipher\KeyPair;
use Stringable;

abstract class AbstractMessage implements Stringable {
	public function __construct(
		protected string $data,
		protected ?InitVector $iv = null,
	) {
		$this->iv = $this->iv ?? new InitVector();
	}

	public function __toString():string {
		return $this->data;
	}

	public function getIv():InitVector {
		return $this->iv;
	}
}
