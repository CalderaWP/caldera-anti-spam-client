<?php


namespace calderawp\AntiSpamClient\Tests;

use calderawp\AntiSpamClient\Content;
use calderawp\AntiSpamClient\ContentController;
use calderawp\AntiSpamClient\Request;
use calderawp\AntiSpamClient\Response;

class ContentControllerTest extends TestCase
{
    /**
     * Test spammy request
     *
     * @covers Response::isSpamResponse()
     * @covers ContentController::check()
     */
    public function testSpamRequest()
    {
        $entity = $this->contentEntityFactory();
        $controller = new SpamMockController($this->getClient(200));
        $response = $controller->check($entity->toRequest($controller->getClient()));
        $responseArrayed = $response->toArray();
        $this->assertArrayHasKey('allow', $responseArrayed);
        $this->assertFalse($responseArrayed[ 'allow' ]);
    }

    /**
     * Test not spammy request
     *
     * @covers Response::isSpamResponse()
     * @covers ContentController::check()
     */
    public function testNotSpammyRequest()
    {
        $entity = $this->contentEntityFactory();
        $controller = new NotSpamMockController($this->getClient(200));
        $response = $controller->check($entity->toRequest($controller->getClient()));
        $responseArrayed = $response->toArray();
        $this->assertArrayHasKey('allow', $responseArrayed);
        $this->assertTrue($responseArrayed[ 'allow' ]);
    }

    /**
     * Test not spammy invalid request
     *
     * @covers Content::validate()
     * @covers Response::isSpamResponse()
     * @covers ContentController::check()
     */
    public function testInvalidRequest()
    {
        $args = $this->contentRequestArgs();
        $args[ 'type' ] = 'pants';
        $entity = Content::fromArray($args);
        $controller = new NotSpamMockController($this->getClient(200));

        $response = $controller->check($entity->toRequest($controller->getClient()));
        $this->assertSame(421, $response->getStatusCode());
        $responseArrayed = $response->toArray();
        $this->assertArrayHasKey('allow', $responseArrayed);
        $this->assertFalse($responseArrayed[ 'allow' ]);

        $this->assertArrayHasKey('errors', $responseArrayed);
        $this->assertArrayHasKey('type', $responseArrayed['errors']);
    }

    /**
     * Test not authorized request
     *
     * @covers Response::unAuthorizedResponse()
     */
    public function testUnAuthorizeRequest()
    {
        $response = Response::unAuthorizedResponse();
        $this->assertSame(401, $response->getStatusCode());
        $responseArrayed = $response->toArray();
        $this->assertArrayHasKey('allow', $responseArrayed);
        $this->assertFalse($responseArrayed[ 'allow' ]);

        $this->assertArrayHasKey('errors', $responseArrayed);
        $this->assertArrayHasKey('authorization', $responseArrayed['errors']);
        $this->assertSame('Unauthorized', $responseArrayed['errors'][ 'authorization' ]);
    }
}
