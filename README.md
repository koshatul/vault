Hashicorp Vault Class for PHP
===============================

A interface for retrieving values from Hashicorp Vault in a PHP Project.

[![Build Status](https://travis-ci.org/Koshatul/vault.png?branch=master)](https://travis-ci.org/Koshatul/vault)
[![Latest Stable Version](https://poser.pugx.org/koshatul/vault/v/stable.png)](https://packagist.org/packages/koshatul/vault)
[![Total Downloads](https://poser.pugx.org/koshatul/vault/downloads.png)](https://packagist.org/packages/koshatul/vault)

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

