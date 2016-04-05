<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

use PHPUnit_Framework_TestCase;

class VaultURITest extends PHPUnit_Framework_TestCase
{

    public function testPassthrough()
    {
		$uri = 'http://127.0.0.1:8200/v1/';
		$vaultURI = new VaultURI($uri);

		$this->assertEquals($uri, $vaultURI->getURI(), 'Test Passthrough Valid URI');
    }

    public function testMissingPort()
    {
    	$uri = 'http://127.0.0.1/v1/';
        $vaultURI = new VaultURI($uri);

        $this->assertEquals($uri, $vaultURI->getURI(), 'Test Missing Port');
    }

    public function testMissingPath()
    {
		$uri = 'http://127.0.0.1:8200/';
		$vaultURI = new VaultURI($uri);

		$this->assertEquals($uri.'v1/', $vaultURI->getURI(), 'Test Missing v1 tag [with path /]');


    	$uri = 'http://127.0.0.1';
        $vaultURI = new VaultURI($uri);

        $this->assertEquals($uri.'/v1/', $vaultURI->getURI(), 'Test Missing v1 tag [without path /]');
    }
}
