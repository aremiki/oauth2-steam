# Steam Provider for OAuth 2.0 Client

This package provides Steam OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client).

## Installation

To install, use Composer:

```
composer require aremiki/oauth2-steam
```

## Usage

Usage is the same as The League's OAuth client, using `Aremiki\OAuth2\Client\Steam` as the provider.

```php
$provider = new \Aremiki\Oauth2Steam\Client\Provider\Steam([
    'clientId' => "YOUR_CLIENT_ID",
    'clientSecret' => "YOUR_CLIENT_SECRET",
    'redirectUri' => "http://your-redirect-uri-passed-in-twitch-dashboard",
]);
```

Testing
---------
```bash
$ ./vendor/bin/phpunit
```