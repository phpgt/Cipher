<?php
namespace Gt\Cipher\Test;

use Gt\Cipher\InitVector;
use Gt\Cipher\Message;
use Gt\Cipher\UriAdapter;
use Gt\Http\Uri;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class URIAdapterTest extends TestCase {
	public function testGetQueryStringParts():void {
		$ivString = "example-iv";
		$cipherString = "example-cipher-text";

		$iv = self::createMock(InitVector::class);
		$iv->method("__toString")
			->willReturn($ivString);
		/** @var MockObject|Message $message */
		$message = self::getMockBuilder(Message::class)
			->disableOriginalConstructor()
			->getMock();
		$message->method("getIv")
			->willReturn($iv);
		$message->method("getCipherText")
			->willReturn($cipherString);

		$sut = new UriAdapter($message);
		$queryStringParts = $sut->getQueryStringParts();
		self::assertCount(2, $queryStringParts);
		self::assertSame($ivString, $queryStringParts[UriAdapter::KEY_IV]);
		self::assertSame($cipherString, $queryStringParts[UriAdapter::KEY_CIPHER]);
	}

	public function testGetQueryStringParts_withExistingQueryString():void {
		$ivString = "example-iv";
		$cipherString = "example-cipher-text";
		$uriQuery = "name=Cody&species=feline";

		$iv = self::createMock(InitVector::class);
		$iv->method("__toString")
			->willReturn($ivString);
		/** @var MockObject|Message $message */
		$message = self::getMockBuilder(Message::class)
			->disableOriginalConstructor()
			->getMock();
		$message->method("getIv")
			->willReturn($iv);
		$message->method("getCipherText")
			->willReturn($cipherString);

		$uri = self::createMock(Uri::class);
		$uri->method("getQuery")->willReturn($uriQuery);

		$sut = new UriAdapter($message, $uri);
		$queryStringParts = $sut->getQueryStringParts();
		self::assertSame("Cody", $queryStringParts["name"]);
		self::assertSame("feline", $queryStringParts["species"]);
		self::assertSame($ivString, $queryStringParts[UriAdapter::KEY_IV]);
		self::assertSame($cipherString, $queryStringParts[UriAdapter::KEY_CIPHER]);
	}

	public function testGetQueryString():void {
		$ivString = "example-iv";
		$cipherString = "example-cipher-text";

		$iv = self::createMock(InitVector::class);
		$iv->method("__toString")
			->willReturn($ivString);
		/** @var MockObject|Message $message */
		$message = self::getMockBuilder(Message::class)
			->disableOriginalConstructor()
			->getMock();
		$message->method("getIv")
			->willReturn($iv);
		$message->method("getCipherText")
			->willReturn($cipherString);

		$sut = new UriAdapter($message);

		self::assertSame(
			"iv=$ivString&cipher=$cipherString",
			$sut->getQueryString(),
		);
	}

	public function testGetQueryString_withExistingQueryString():void {
		$ivString = "example-iv";
		$cipherString = "example-cipher-text";
		$uriQuery = "name=Cody&species=feline";

		$iv = self::createMock(InitVector::class);
		$iv->method("__toString")
			->willReturn($ivString);
		/** @var MockObject|Message $message */
		$message = self::getMockBuilder(Message::class)
			->disableOriginalConstructor()
			->getMock();
		$message->method("getIv")
			->willReturn($iv);
		$message->method("getCipherText")
			->willReturn($cipherString);

		$uri = self::createMock(Uri::class);
		$uri->method("getQuery")->willReturn($uriQuery);

		$sut = new UriAdapter($message, $uri);
		$queryString = $sut->getQueryString();
// Here we're testing the order of the query string too - we want the existing
// parameters to always appear at the start of the string, appending our
// keys to the end.
		self::assertSame("name=Cody&species=feline&iv=$ivString&cipher=$cipherString", $queryString);
	}

	public function testGetQueryString_withExistingFullUri():void {
		$ivString = "example-iv";
		$cipherString = "example-cipher-text";
		$uriScheme = "https";
		$uriHost = "php.gt";
		$uriPath = "/cipher";
		$uriQuery = "name=Cody&species=feline";
		$uriFullString = "$uriScheme://$uriHost$uriPath?$uriQuery";

		$iv = self::createMock(InitVector::class);
		$iv->method("__toString")
			->willReturn($ivString);
		/** @var MockObject|Message $message */
		$message = self::getMockBuilder(Message::class)
			->disableOriginalConstructor()
			->getMock();
		$message->method("getIv")
			->willReturn($iv);
		$message->method("getCipherText")
			->willReturn($cipherString);

		$uri = self::createMock(Uri::class);
		$uri->method("__toString")->willReturn($uriFullString);

		$sut = new UriAdapter($message, $uri);
		$testUri = $sut->getUri();
		self::assertSame(
			"name=Cody&species=feline&iv=$ivString&cipher=$cipherString",
			$testUri->getQuery(),
		);
		self::assertSame("https", $testUri->getScheme());
		self::assertSame("php.gt", $testUri->getHost());
		self::assertSame("/cipher", $testUri->getPath());
	}

	public function testToString_withExistingFullUri():void {
		$ivString = "example-iv";
		$cipherString = "example-cipher-text";
		$uriScheme = "https";
		$uriHost = "php.gt";
		$uriPath = "/cipher";
		$uriQuery = "name=Cody&species=feline";
		$uriFullString = "$uriScheme://$uriHost$uriPath?$uriQuery";

		$iv = self::createMock(InitVector::class);
		$iv->method("__toString")
			->willReturn($ivString);
		/** @var MockObject|Message $message */
		$message = self::getMockBuilder(Message::class)
			->disableOriginalConstructor()
			->getMock();
		$message->method("getIv")
			->willReturn($iv);
		$message->method("getCipherText")
			->willReturn($cipherString);

		$uri = self::createMock(Uri::class);
		$uri->method("__toString")->willReturn($uriFullString);

		$sut = new UriAdapter($message, $uri);
		self::assertSame(
			"https://php.gt/cipher?name=Cody&species=feline&iv=$ivString&cipher=$cipherString",
			(string)$sut,
		);
	}
}
