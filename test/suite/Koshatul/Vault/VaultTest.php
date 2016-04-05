<?php

/*
 * Copyright © 2013 Kosh
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Koshatul\Vault;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit_Framework_TestCase;

class VaultTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->responseBody = [
            'lease_id' => null, 'renewable' => null, 'lease_duration' => 2592000,
            'data' => [],
            'warnings' => null, 'auth' => null,
        ];
        $vaultURI = new VaultURI('http://127.0.0.1:8200/v1/');
        $vaultAuthToken = new VaultAuthToken('a3ae3c01-4f07-2ac8-xxxx-2f51bdcbdc76');
        $this->vault = new Vault($vaultURI, $vaultAuthToken);
    }

    public function testGET()
    {
        $responseBody = $this->responseBody;
        $responseBody['data']['bingo'] = 'a490ed98-15ea-4d0a-b0fd-9d46f6521504';
        $mock = new MockHandler([
            new Response(200,
                [],
                json_encode($responseBody)
            ),
            new Response(404,
                []
            ),
            new Response(204,
                []
            ),
        ]);

        $vaultURI = new VaultURI('http://127.0.0.1:8200/v1/');
        $vaultAuthToken = new VaultAuthToken('a3ae3c01-4f07-2ac8-xxxx-2f51bdcbdc76');
        $vault = new Vault($vaultURI, $vaultAuthToken);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $vault->setClient($client);

        $value = $vault->read('test/value');

        $this->assertEquals('a490ed98-15ea-4d0a-b0fd-9d46f6521504', $value->get('bingo'), 'Test Retrieve Value, Value Exists along with field');
        $this->assertNull($value->get('doesnotexist'), 'Test Retrieve Value, Value Exists, Field does not exist');

        $value = $vault->read('test/invalid');

        $this->assertInstanceOf('\\Koshatul\\Vault\\VaultResponse', $value, 'Test Retrieve Value, Value does not exist, VaultResponse returned');
        $this->assertEquals(null, $value->get(), 'Test Retrieve Value, Value does not exist');

        $value = $vault->read('test/shouldnotget');

        $this->assertInstanceOf('\\Koshatul\\Vault\\VaultResponse', $value, 'Test Retrieve Value, Value does not exist, VaultResponse returned');
        $this->assertEquals(null, $value->get(), 'Test Retrieve Value, Value does not exist');
    }

    public function testPOST()
    {
        $mock = new MockHandler([
            new Response(204),
            new Response(204),
            new Response(403),
        ]);

        $vaultURI = new VaultURI('http://127.0.0.1:8200/v1/');
        $vaultAuthToken = new VaultAuthToken('a3ae3c01-4f07-2ac8-xxxx-2f51bdcbdc76');
        $vault = new Vault($vaultURI, $vaultAuthToken);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $vault->setClient($client);

        $value = $vault->write('test/value', ['value' => 'testing', 'excited' => 'yes']);
        $this->assertEquals(true, $value, 'Test Setting Value');

        $value = $vault->write('test/value', 'testing');
        $this->assertEquals(true, $value, 'Test Setting Single Value');

        $value = $vault->write('test/value', 'testing');
        $this->assertEquals(null, $value, 'Test Setting Single Value, Forbidden');
    }

    public function testUnseal()
    {
        $mock = new MockHandler([
            new Response(200, [], json_encode(['sealed' => true])),
            new Response(200, [], json_encode(['sealed' => false])),
            new Response(404, []),
        ]);

        $vaultURI = new VaultURI('http://127.0.0.1:8200/v1/');
        $vaultAuthToken = new VaultAuthToken('a3ae3c01-4f07-2ac8-xxxx-2f51bdcbdc76');
        $vault = new Vault($vaultURI, $vaultAuthToken);

        $client = new Client(['handler' => HandlerStack::create($mock)]);
        $vault->setClient($client);

        $value = $vault->unseal('key1');
        $this->assertEquals(true, $value, 'Test Unseal Key 1 (Still Sealed)');

        $value = $vault->unseal('key2');
        $this->assertEquals(false, $value, 'Test Unseal Key 2 (Unsealed)');

        $value = $vault->unseal('failed');
        $this->assertEquals(null, $value, 'Test Unseal No Access');
    }
}
