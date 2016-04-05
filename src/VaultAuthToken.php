<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

class VaultAuthToken implements VaultAuth
{
    protected $_token;

    public function __construct($token)
    {
        $this->_token = $token;
    }

    public function getStackFunction()
    {
        return Middleware::mapRequest(function (RequestInterface $request) {
            // Notice that we have to return a request object
            return $request->withHeader('X-Vault-Token', $this->_token);
        });
    }
}
