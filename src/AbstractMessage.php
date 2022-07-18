<?php
namespace Gt\Cipher;

use Stringable;

abstract class AbstractMessage implements Stringable {
	public function __construct(
		protected string $data,
		protected ?KeyPair $keyPair = null,
		protected ?InitVector $iv = null,
	) {
		$this->keyPair = $this->keyPair ?? new KeyPair();
		$this->iv = $this->iv ?? new InitVector();
	}

	abstract public function __toString():string;
}
