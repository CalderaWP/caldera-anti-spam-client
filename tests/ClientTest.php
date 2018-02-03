<?php


namespace calderawp\AntiSpamClient\Tests;

use calderawp\AntiSpamClient\Client;
use calderawp\AntiSpamClient\Exception;

class ClientTest extends TestCase
{
    const  VALIDAPIKEY  = '12345';

    /**
     * Test valid API key

     * @covers Client::request()
     * @covers Client::checkContent()
     */
    public function testValidAuth()
    {
        $client = $this->getClient(200);
        $client->checkContent($this->contentEntityFactory());

        $this->assertSame(200, $client->getLastResponseCode());
    }

    /**
     * Test headers
     *
     * @covers Client::createHeaders()
     */
    public function testHeaders()
    {
        foreach ([ 200, 401 ] as $status) {
            $testsRan = false;
            $client = $this->getClient($status);
            $headers = $client->createHeaders();
            $this->assertArrayHasKey($client::HEADER, $headers);
            $entity = $this->contentEntityFactory();
            if (200 === $status) {
                $check = $client->checkContent($entity);
                $this->assertSame(self::VALIDAPIKEY, $headers[$client::HEADER]);
                $this->assertTrue(is_bool($check));
                $testsRan = true;
            } else {
                $this->expectException(Exception::class);
                $check = $client->checkContent($entity);
                $this->assertNotSame(self::VALIDAPIKEY, $headers[$client::HEADER]);
                $testsRan = true;
            }

            $this->assertTrue($testsRan);
        }
    }

    /**
     * Test URI for API request
     *
     * @covers Client::getEndpointUrl()
     */
    public function testUri()
    {
        $client = $this->getClient(200);
        $entity = $this->contentEntityFactory();
        $request = $entity->toRequest($client);
        $this->assertSame(
            'http://mocked.xyz/api/v1/content',
            $client->getEndpointUrl('content')
        );

        $this->assertSame(
            'http://mocked.xyz/api/v1/content',
            $request->getUri()->__toString()
        );
    }
}
