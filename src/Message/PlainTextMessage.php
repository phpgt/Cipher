<?php
namespace GT\Cipher\Message;

use GT\Cipher\CipherText;
use GT\Cipher\Key;

class PlainTextMessage extends AbstractMessage {
	public function encrypt(Key $key):CipherText {
		return new CipherText(
			$this->data,
			$this->iv,
			$key,
		);
	}
}
