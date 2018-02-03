<?php


namespace calderawp\AntiSpamClient\Tests;

use calderawp\AntiSpamClient\Content;
use calderawp\interop\Entities\EmailSender;

class RequestTest extends TestCase
{
    /**
     *
     */
    public function testBodySame()
    {
        $client = $this->getClient(200);
        $entity = $this->contentEntityFactory();
        $request = $entity->toRequest($client);
        $client->checkContent($entity);
        $body = (array)\GuzzleHttp\json_decode($request->getBody());
        $submitter = EmailSender::fromArray((array)$body['submitter']);

        $body['submitter'] = $submitter->toArray();
        $this->assertSame($entity->toArray(), $body);
    }

    /**
     * Test turing entity into request and back again
     */
    public function testFromEntity()
    {
        $client = $this->getClient(200);
        $entity = $this->contentEntityFactory();
        $request = $entity->toRequest($client);
        $fromRequest = Content::fromRequest($request);
        $this->assertEquals($entity, $fromRequest);
    }
}
