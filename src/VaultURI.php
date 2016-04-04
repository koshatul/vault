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
        $this->_uri = $vaultURI;
    }

    public function getURI()
    {
        return $this->_uri;
    }
}
