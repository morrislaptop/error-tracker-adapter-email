# error-tracker-adapter-email

[![Build
Status](https://secure.travis-ci.org/morrislaptop/error-tracker-adapter-email.png)](http://travis-ci.org/morrislaptop/error-tracker-adapter-email)
[![Total
Downloads](https://poser.pugx.org/morrislaptop/error-tracker-adapter-email/downloads.png)](https://packagist.org/packages/morrislaptop/error-tracker-adapter-email)
[![Latest Stable
Version](https://poser.pugx.org/morrislaptop/error-tracker-adapter-email/v/stable.png)](https://packagist.org/packages/morrislaptop/error-tracker-adapter-email)

Send email reports on exceptions, using the Tracker interface from [error-tracker-adapter](https://github.com/morrislaptop/error-tracker-adapter)

## Installation

The recommended way to install is through [Composer](http://getcomposer.org):

```
$ composer require morrislaptop/error-tracker-adapter-email
```

## Usage

The use of this library is a _reporter_ and not a renderer. So it's recommended that you handle exceptions in your application with your own class and then report to this library if it's the right error type and/or environment.

```php
<?php

// Setup dependencies (much easier with a IoC container)
$transport = new Swift_SendmailTransport();
$mailer = new Swift_Mailer($transport);
$message = new Swift_Message();
$message->addTo('craig.michael.morris@gmail.com');
$message->setFrom('craig.michael.morris@gmail.com');
$body = new Body(new VarCloner(), new CliDumper());
$compiler = new Compiler(new CommonMarkConverter(), new CssToInlineStyles());

// Create reporter
$email = new Email($mailer, $message, $body, $compiler);

// Act.
$exception = new DomainException('Testing a domain exception');
$email->report($exception, ['only' => 'testing', 'user' => Session::all()]);
```

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

See [CONTRIBUTING](CONTRIBUTING.md) file.

## Unit Tests

In order to run the test suite, install the developement dependencies:

```
$ composer install --dev
```

Then, run the following command:

```
$ phpunit && phpspec run
```

MailTrap is used to check that emails are sent in the integration tests. You may copy phpunit.xml.dist and set your API keys in there to complete your tests.

## Versioning

Follows [Semantic Versioning](http://semver.org/).

## License

MIT license. For the full copyright and license information, please read the LICENSE file that was distributed with this source code.
