# Browser Language

Detect languages supported by user browser in PHP.

## How it works?

While requesting for data, browsers are sending `Accept-Language` header to server. It contains information about which language user can understand. We can use this value to serve content for users with their preferred language automatically.

Unfourunately, `Accept-Language` header is a bit complicated and needs parsing. That is the reason why you may need this package.

## Installation

Use composer to install package in your project.

```sh
composer require dartui/browser-language
```

## Usage

```php
use Dartui\BrowserLanguage\BrowserLanguage;

/**
 * Constructor tries to get Accept-Language value from $_SERVER superglobal.
 */
$browserLanguage = new BrowserLanguage();

/**
 * Additionally you can pass Accept-Language header value
 * or hardcoded value by yourself.
 */
$browserLanguage = new BrowserLanguage('en-US,en;q=0.5,pl;q=0.3');

/**
 * Get the list of all supported languages sorted by factor.
 * Example: [en-US, en, pl]
 */
$allLanguages = $browserLanguage->all();

/**
 * Get the best match for user browser language.
 * Example: en-US
 */
$language = $browserLanguage->best();
```