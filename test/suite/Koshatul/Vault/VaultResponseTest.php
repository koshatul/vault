<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

use PHPUnit_Framework_TestCase;

class VaultResponseTest extends PHPUnit_Framework_TestCase
{
	public function setUp()
	{
        $this->responseBody = [
            'lease_id' => null, 'renewable' => null, 'lease_duration' => 2592000,
            'data' => [],
            'warnings' => null, 'auth' => null,
        ];
	}

    public function testInvalidData()
    {
		$data = '{notvalidjson';
		$vaultResponse = new VaultResponse($data);

		$this->assertNull($vaultResponse->get('anything'), 'JSON invalid, get field');

		$this->assertNull($vaultResponse->get(), 'JSON invalid, get with no field');
    }

    public function testInvalidField()
    {
    	$data = $this->responseBody;
    	$data['data']['testfield'] = 'testvalue';

    	$vaultResponse = new VaultResponse(json_encode($data));
    	$this->assertNull($vaultResponse->get('doesnotexist'), 'Data is valid, field does not exist');
    	$this->assertEquals('testvalue', $vaultResponse->get('testfield'), 'Data is valid, testing valid field');
    }

    public function testInvalidResponse()
    {
    	$data = $this->responseBody;
    	unset($data['data']);

    	$vaultResponse = new VaultResponse(json_encode($data));
    	$this->assertNull($vaultResponse->get('doesnotexist'), 'Data is missing from response, field does not exist');

    	$this->assertNull($vaultResponse->getAuth('doesnotexist'), 'Data is missing from response, field does not exist');

    }

    public function testGetEntireFieldArray()
    {
    	$data = $this->responseBody;
    	$dataArray = ['testfield' => 'testvalue', 'foo' => 'bar'];
    	$data['data'] = $dataArray;

    	$vaultResponse = new VaultResponse(json_encode($data));
    	$this->assertEquals($dataArray, $vaultResponse->get(), 'Data is valid, field is null');
    }

    public function testErrors()
    {
    	$data = [
    	'errors' => ['Error #1'],
    	];

    	$vaultResponse = new VaultResponse(json_encode($data));
    	$this->assertEquals($data['errors'], $vaultResponse->errors(), 'Errors exists, returns error array');

    	$data = $this->responseBody;
    	$data['data']['testfield'] = 'testvalue';
    	$vaultResponse = new VaultResponse(json_encode($data));
    	$this->assertNull($vaultResponse->errors(), 'Response Valid, No Errors');

		$data = '{notvalidjson';
		$vaultResponse = new VaultResponse($data);
    	$this->assertNull($vaultResponse->errors(), 'Response Invalid, No Errors');

    }


}
