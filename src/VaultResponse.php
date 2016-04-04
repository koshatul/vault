<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

class VaultResponse
{
	protected $_json;
	protected $_data;

	public function __construct($jsonResponse)
	{
		$this->_json = $jsonResponse;
		if (!is_null($this->_json)) {
			$this->_data = json_decode($jsonResponse, true);			
		}
	}

	public function errors()
	{
		if (!is_array($this->_data)) {
			return null;
		}
		if (array_key_exists('errors', $this->_data)) {
			return $this->_data['errors'];
		}
		return null;
	}

	public function _get($source, $field = null) 
	{
		if (!is_array($this->_data)) {
			return null;
		}
		if (!array_key_exists($source, $this->_data)) {
			return null;
		}
		if (!is_null($field)) {
			if (array_key_exists($field, $this->_data[$source])) {
				return $this->_data[$source][$field];
			} else {
				return null;
			}
		} else {
			return $this->_data[$source];
		}
	}

	public function get($field = null) 
	{
		return $this->_get('data', $field);
	}

	public function getAuth($field = null) 
	{
		return $this->_get('auth', $field);
	}

}
