Two-way encryption of messages for secure plain text transmission.
==================================================================

When messages are passed between two systems over a public network, encryption tools are needed to protect the content in transit. Encrypting and decrypting messages correctly can be fiddly and error-prone, so this library keeps the process small and explicit through the `PlainTextMessage`, `EncryptedMessage`, `CipherText`, `Key`, and `InitVector` abstractions.

Pass your secret message to the `PlainTextMessage` constructor, then call `encrypt()` with a shared `Key` to produce a `CipherText`. The encrypted payload is represented by the cipher text itself plus the IV returned by `getIv()`. Those values can then be passed to the receiver by any communication mechanism, with only the holder of the same shared key able to decrypt the original message.

On the receiving side, construct an `EncryptedMessage` with the incoming cipher text and IV, then call `decrypt()` with the same `Key` to recover the original plain text.

The `CipherText` class also provides a `getUri()` method for creating a pre-encoded URI. A URI containing `cipher` and `iv` query string parameters can then be passed to `EncryptedUri` and decrypted back into a `PlainTextMessage`.

***

<a href="https://github.com/PhpGt/Cipher/actions" target="_blank">
	<img src="https://badge.status.php.gt/cipher-build.svg" alt="Build status" />
</a>
<a href="https://app.codacy.com/gh/PhpGt/Cipher" target="_blank">
	<img src="https://badge.status.php.gt/cipher-quality.svg" alt="Code quality" />
</a>
<a href="https://app.codecov.io/gh/PhpGt/Cipher" target="_blank">
	<img src="https://badge.status.php.gt/cipher-coverage.svg" alt="Code coverage" />
</a>
<a href="https://packagist.org/packages/PhpGt/Cipher" target="_blank">
	<img src="https://badge.status.php.gt/cipher-version.svg" alt="Current version" />
</a>
<a href="http://www.php.gt/cipher" target="_blank">
	<img src="https://badge.status.php.gt/cipher-docs.svg" alt="PHP.Gt/Cipher documentation" />
</a>

## Example usage: transmit an encrypted message over a query string

`sender.php`:

```php
use \GT\Cipher\Message\PlainTextMessage;
use \GT\Cipher\Message\EncryptedMessage;

$privateKey = "This can be any string, but a long random string is best.";

$message = new PlainTextMessage("Hello, PHP.Gt!");
$cipherText = $message->encrypt($privateKey);
header("Location: " . $cipherText->getUri("/receiver.php"));
```

`receiver.php`:

```php
// This key must be the same on the sender and receiver!
use GT\Cipher\EncryptedUri;

$privateKey = "This can be any string, but a long random string is best.";

$uri = new EncryptedUri($_SERVER["REQUEST_URI"]);
$plainText = $uri->decryptMessage($privateKey);
echo $plainText;
// Output: Hello, PHP.Gt!
```

# Proudly sponsored by

[JetBrains Open Source sponsorship program](https://www.jetbrains.com/community/opensource/)

[![JetBrains logo.](https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg)](https://www.jetbrains.com/community/opensource/)
