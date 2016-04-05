<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

class VaultURI
{
    protected $_uri;

    public function __construct($vaultURI)
    {
    	$testURI = parse_url($vaultURI);
    	if (
            (!array_key_exists('path', $testURI)) 
            or (array_key_exists('path', $testURI) and in_array($testURI['path'], ['/', '']))
        ) {
    		$vaultURI = $testURI['scheme']
                .'://'
                .$testURI['host']
                .(array_key_exists('port', $testURI) ? ':'.$testURI['port'] : '')
                .'/v1/';
    	}
        $this->_uri = $vaultURI;
    }

    public function getURI()
    {
        return $this->_uri;
    }
}
