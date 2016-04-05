#!/usr/bin/env php
<?php

$packageRoot = dirname(__DIR__);
$matches = array();
if (preg_match('{^(.*)/vendor/.+/.+$}', $packageRoot, $matches)) {
    require $matches[1] . '/vendor/autoload.php';
} else {
    require $packageRoot . '/vendor/autoload.php';
}

$shortopts  = "";
$shortopts .= "t:";
$shortopts .= "a:";
$shortopts .= "k:";

$longopts  = array(
    "token:",
    "addr:",
    "key:",
);

$options = getopt($shortopts, $longopts);

function die_usage() {
	echo "usage: ".__FILE__." --token=<token> --addr=<addr> --key=<key>";
	exit(99);
}

$vault_token = null;
$vault_addr  = null;
$vault_key   = null;

foreach ($options as $key => $value) {
	switch($key) {
		case 't':
		case 'token':
			$vault_token = $value;
			break;
		case 'a':
		case 'addr':
			$vault_addr = $value;
			break;
		case 'k':
		case 'key':
			$vault_key = $value;
			break;
		default:
			die_usage();
	}
}

if (is_null($vault_addr) or is_null($vault_token) or is_null($vault_key)) {
	die_usage();
}

use Koshatul\Vault\Vault;
use Koshatul\Vault\VaultAuthToken;
use Koshatul\Vault\VaultURI;

$vaultURI = new VaultURI($vault_addr);

$vaultAuthToken = new VaultAuthToken($vault_token);
$vault = new Vault($vaultURI, $vaultAuthToken);

$value = $vault->read($vault_key);

$env = $value->get();

if (is_array($env)) {
	foreach ($env as $key => $value) {
		echo "export ".$key."=".escapeshellarg($value).PHP_EOL;
	}	
}
