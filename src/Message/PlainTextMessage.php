<?php
namespace Gt\Cipher\Message;

use Gt\Cipher\CipherText;
use Gt\Cipher\Key;

class PlainTextMessage extends AbstractMessage {
	public function encrypt(Key $key):CipherText {
		return new CipherText(
			$this->data,
			$this->iv,
			$key,
		);
	}
}
