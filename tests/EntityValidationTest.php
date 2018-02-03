<?php


namespace calderawp\AntiSpamClient\Tests;

use calderawp\AntiSpamClient\Content;
use Respect\Validation\Validator as V;

class EntityValidationTest extends TestCase
{

    /**
     *
     * @covers TestCase::contentEntityFactory()
     * @covers Content::isValid();
     * @covers Content::validate();
     * @covers Content::rules();
     */
    public function testWithDefaults()
    {
        $entity = $this->contentEntityFactory();
        $this->assertTrue($entity->isValid());
    }

    /**
     * Test required are required
     *
     *
     * @covers TestCase::contentEntityFactory()
     * @covers Content::isValid();
     * @covers Content::validate();
     * @covers Content::rules();
     */
    public function testRequired()
    {
        $requireds = [
            'url',
            'site_url',
            'user_agent',
            'referrer'
        ];
        foreach ($requireds as $required) {
            $args = $this->contentRequestArgs();
            unset($args[ $required ]);
            $entity = Content::fromArray($args);
            $this->assertTrue(is_string($entity->validate()->getError($required)));
            $this->assertFalse($entity->isValid());
        }
    }

    /**
     *
     * @covers TestCase::contentEntityFactory()
     * @covers Content::isValid();
     * @covers Content::validate();
     * @covers Content::rules();
     */
    public function testInvalidType()
    {
        $args = $this->contentRequestArgs();
        $args[ 'type' ] = 'pants';
        $entity = Content::fromArray($args);
        $this->assertTrue(is_string($entity->validate()->getError('type')));
        $this->assertNotEmpty($entity->validate()->getError('type'));
        $this->assertFalse($entity->isValid());
    }


    /**
     *
     * @covers TestCase::contentEntityFactory()
     * @covers Content::isValid();
     * @covers Content::validate();
     * @covers Content::rules();
     */
    public function testInvalidIp()
    {
        $this->assertFalse(V::notEmpty()->addRule(V::ip())->validate('1.pants'));
        $args = $this->contentRequestArgs();
        $args[ 'ip' ] = '1.pants';
        $entity = Content::fromArray($args);
        $this->assertSame($args[ 'ip' ], $entity->ip);
        $this->assertFalse($entity->isValid());
    }
}
