<?php
namespace Gt\Cipher;

use Exception;

abstract class AbstractMessage {
	const DEFAULT_ALGO = "aes-256-ctr";
	const DEFAULT_OPTIONS = [
		"algo" => self::DEFAULT_ALGO,
	];

	protected string $algo;
	protected InitVector $iv;

	/**
	 * @param array<string, string> $options
	 */
	public function __construct(
		protected string $data,
		protected string $privateKey,
		InitVector $iv = null,
		array $options = self::DEFAULT_OPTIONS,
	) {
		if(is_null($iv)) {
			$length = openssl_cipher_iv_length((string)$options["algo"]);
			if(false === $length) {
				throw new CipherException("Unknown cipher algorithm: $options[algo]");
			}
			$iv = new InitVector($length);
		}
		$this->iv = $iv;
		$this->algo = (string)$options["algo"];
	}
}
