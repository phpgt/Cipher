<?php
namespace Gt\Cipher;

use Exception;

class Message {
	const DEFAULT_OPTIONS = [
		"algo" => "aes-256-ctr",
	];

	private string $algo;
	private InitVector $iv;

	/**
	 * @param array<string, string|int> $options
	 */
	public function __construct(
		private string $message,
		private string $privateKey,
		InitVector $iv = null,
		array $options = self::DEFAULT_OPTIONS,
	) {
		if(is_null($iv)) {
			try {
				$iv = new InitVector(
					openssl_cipher_iv_length((string)$options["algo"]) ?: 0
				);
			}
			catch(Exception $e) {
				throw new CipherException($e->getMessage());
			}
		}
		$this->iv = $iv;

		$this->algo = (string)$options["algo"];
	}

	public function getIv():InitVector {
		return $this->iv;
	}

	public function getCipherText():string {
		try {
			$encrypted = (string)openssl_encrypt(
				$this->message,
				$this->algo,
				$this->privateKey,
				0,
				$this->iv->getBytes(),
			);
			return bin2hex($encrypted);
		}
		catch(Exception $e) {
			throw new CipherException($e->getMessage());
		}
	}
}
