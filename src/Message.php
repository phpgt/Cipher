<?php
namespace Gt\Cipher;

class Message {
	const DEFAULT_OPTIONS = [
		"algo" => "aes-256-ctr",
	];

	private string $algo;

	public function __construct(
		private string $message,
		private string $privateKey,
		private ?InitVector $iv = null,
		array $options = self::DEFAULT_OPTIONS,
	) {
		if(is_null($this->iv)) {
			$this->iv = new InitVector(
				openssl_cipher_iv_length($options["algo"])
			);
		}

		$this->algo = $options["algo"];
	}

	public function getIv():InitVector {
		return $this->iv;
	}

	public function getCipherText():string {
		$encrypted = openssl_encrypt(
			$this->message,
			$this->algo,
			$this->privateKey,
			0,
			$this->iv->getBytes(),
		);
		return bin2hex($encrypted);
	}
}
