~Hashicorp Vault Class for PHP~
===============================

**This is unmaintained, I haven't used it in quite a while and don't have the time to update and test it properly.**

A interface for retrieving values from Hashicorp Vault in a PHP Project.

[![Build Status](https://travis-ci.org/Koshatul/vault.svg?branch=master)](https://travis-ci.org/Koshatul/vault)
[![Latest Stable Version](https://poser.pugx.org/koshatul/vault/v/stable)](https://packagist.org/packages/koshatul/vault)
[![Total Downloads](https://poser.pugx.org/koshatul/vault/downloads)](https://packagist.org/packages/koshatul/vault)
[![Latest Unstable Version](https://poser.pugx.org/koshatul/vault/v/unstable)](https://packagist.org/packages/koshatul/vault)
[![License](https://poser.pugx.org/koshatul/vault/license)](https://packagist.org/packages/koshatul/vault)

Installation
------------

Use [Composer](http://getcomposer.org/) to install the package:

Add the following to your `composer.json` and run `composer update`.

```json
"require": {
    "koshatul/vault": "~1.0"
}
```

Usage
-----
You can use this package to get configuration from a global or specific configuration store.

```php
use Koshatul\Vault\Vault;
use Koshatul\Vault\VaultURI;
use Koshatul\Vault\VaultAuthToken;

$vaultURI = new VaultURI("http://127.0.0.1:8200/v1/");
$vaultAuthToken = new VaultAuthToken("tttttttt-wwww-xxxx-yyyy-zzzzzzzzzzzz");
$vault = new Vault($vaultURI, $vaultAuthToken);

$vault->write('secret/foo' ['pear' => 'table']);

$testValue = $vault->read('secret/foo');
echo "Pear: ".$testValue->get('pear');
```

