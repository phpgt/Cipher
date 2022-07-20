Two-way encryption of messages for secure plain text transmission.
==================================================================

When messages are passed between two systems via a public network, encryption tools must be used to secure the communication channel. The process of encrypting and decrypting a message is complex and prone to errors, but is simplified in this repository by providing the `PlainTextMessage` and `EncryptedMessage` class abstractions.

Pass your secret message to the `PlainTextMessage` constructor along with a private key, and you can call `encrypt()` to convert it into an `EncryptedMessage`. An `EncryptedMessage` is represented by a Cipher and IV value via the `getCipherText()` and `getIv()` functions. These two strings can be passed to the receiver by any communication mechanism, safe in the knowledge that the contents can not be read without the private key.

On the receiver, construct another `EncryptedMessage` with the incoming cipher and IV, and the original message can be read using `decrypt()` 

The `CipherText` class also exposes a `getUri()` function, for creating a pre-encoded URI. A URI with `cipher` and `iv` querystring parameters can be passed to the `EncryptedUri` class to decrypt back into a `PlainTextMessage`.

***

<a href="https://github.com/PhpGt/Cipher/actions" target="_blank">
	<img src="https://badge.status.php.gt/cipher-build.svg" alt="Build status" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/Cipher" target="_blank">
	<img src="https://badge.status.php.gt/cipher-quality.svg" alt="Code quality" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/Cipher" target="_blank">
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
use \Gt\Cipher\Message\PlainTextMessage;
use \Gt\Cipher\Message\EncryptedMessage;

$privateKey = "This can be any string, but a long random string is best.";

$message = new PlainTextMessage("Hello, PHP.Gt!");
$cipherText = $message->encrypt($privateKey);
header("Location: " . $cipherText->getUri("/receiver.php"));
```

`receiver.php`:

```php
// This key must be the same on the sender and receiver!
use Gt\Cipher\EncryptedUri;

$privateKey = "This can be any string, but a long random string is best.";

$uri = new EncryptedUri($_SERVER["REQUEST_URI"]);
$plainText = $uri->decryptMessage($privateKey);
echo $plainText;
// Output: Hello, PHP.Gt!
```
