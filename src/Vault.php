<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\Client;

class Vault
{
	protected $_stack;
	protected $_client;
	protected $_auth;
	protected $_uri;

	public function __construct(VaultURI $vaultURI, VaultAuth $vaultAuth)
	{
		$this->_uri = $vaultURI;
		$this->_auth = $vaultAuth;
		$this->_stack = new HandlerStack();
		$this->_stack->setHandler(new CurlHandler());
		// $this->_stack->push($this->_add_token());
		$this->_stack->push($this->_auth->getStackFunction());
		$this->_client = new Client(
			[
				'handler' => $this->_stack,
				'base_uri' => $this->_uri->getURI(),
			]
		);
	}

	public function setClient(Client $client)
	{
		$this->_client = $client;
	}

	public function write($key, $data)
	{
		if (!is_array($data)) {
			$data = array('value' => $data);
		}
		return $this->_POST($key, $data);
	}

	public function read($key)
	{
		$response = $this->_GET($key);
		return new VaultResponse($response);
	}

	public function unseal($key)
	{
		$response = $this->_PUT('sys/unseal', ['key' => $key]);
		if (is_string($response)) {
			$response = json_decode($response, true);
			return $response['sealed'];
		}
		return null;
	}

	public function _requestWithData($method, $uri, $data)
	{
		$response = $this->_client->request($method, $uri, [ 'json' => $data ]);
		//echo "Status Code: ".$response->getStatusCode().PHP_EOL;
		switch ($response->getStatusCode()) {
			case 200:
			case 404:
				return $response->getBody()->getContents();
			case 204:
				return true;
			default:
				return false;
		}
	}

	public function _POST($uri, $data)
	{
		return $this->_requestWithData('POST', $uri, $data);
	}

	public function _PUT($uri, $data)
	{
		return $this->_requestWithData('PUT', $uri, $data);
	}

	public function _GET($uri)
	{
		$response = $this->_client->get($uri);
		switch ($response->getStatusCode()) {
			case 200:
				return $response->getBody()->getContents();
			default:
				return null;

		}
	}

}