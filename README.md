# Caldera Anti-Spam Client
This is the PHP client for the Caldera Anti-Spam service.

## Usage
1. Acquire API key.
1. `new  calderawp\AntiSpamClient\Client\Client($apiKey, $apiUrl, $guzzle );`
    * Read /tests/ClientTest and tests/RequestTest

## Development
* Build: `composer install`
* Tests and sniffs and lints: `composer tests`
* Tests only: `composer test`

## FAQ
* What is this?
This the a PHP client for an anti-spam service we use as part of [Caldera Forms Pro](https://calderaforms.com/pro).
* How can I use this?
Subscribe to [Caldera Forms Pro](https://calderaforms.com/pro) and upgrade Caldera Forms to version 1.6.0 or later. 
* Can I have an API key?
Are you the [Caldera Forms Pro](https://calderaforms.com/pro) web app? If not, no.
* Seriously, I can't use this?
For now, no, you may not. Seriously, there is one API key. This service does not have a "more than one API key" feature yet.

## Copyright/ License
Copyright 2018 Josh Pollock for CalderaWP LLC. Licensed under the terms of the GNU GPL version 2 or later.