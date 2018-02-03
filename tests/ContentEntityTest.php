<?php


namespace calderawp\AntiSpamClient\Tests;

use calderawp\AntiSpamClient\Content;
use calderawp\AntiSpamClient\Entity;
use calderawp\interop\Entities\EmailSender;

class ContentEntityTest extends TestCase
{

    /**
     * Test set invalid property
     *
     * @covers Content::$allowed
     * @covers Entity::__get()
     * @covers Content::__get()
     * @covers Entity::__set()
     * @covers Content::__set()
     * @covers Entity::toArray()
     * @covers Content::toArray()
     */
    public function testSetInvalidProp()
    {
        $entity = new Content();
        $entity->foo = 'bar';
        $this->assertArrayNotHasKey('bar', $entity->toArray());
    }

    /**
     * Test conversion to array
     *
     * @covers Entity::toArray()
     * @covers Content::toArray()
     * @covers Entity::fromArray()
     * @covers Content::fromArray()
     * @covers Entity::getAllowedAttributes()
     * @covers Content::getAllowedAttributes()
     * @covers Entity::__get()
     * @covers Content::__get()
     * @covers Entity::__set()
     * @covers Content::__set()
     */
    public function testToArray()
    {
        $expect = $this->contentRequestArgs();
        $entity = Content::fromArray($expect);
        $expectedSubmitter = new EmailSender();
        $expect[ 'name' ] = $expect['submitter'][ 'name'];
        $expect[ 'email' ] = $expect['submitter'][ 'email'];
        $expectedSubmitter->name = $expect['submitter'][ 'name'];
        $expectedSubmitter->email = $expect['submitter'][ 'email'];
        $expect[ 'submitter' ] = $expectedSubmitter->toArray();
        $this->assertEquals($expect, $entity->toArray());
    }

    /**
     * @group contentSubmitter
     */
    public function testGetSubmitter()
    {
        $entity = new Content();
        $this->assertTrue(is_object($entity->getSubmitter()));
        $this->assertNull($entity->getSubmitter()->email);
        $this->assertNull($entity->getSubmitter()->name);
    }

    /**
     * @group contentSubmitter
     */
    public function testNameEmailNotSet()
    {
        $entity = new Content();
        $this->assertEquals('', $entity->getName());
        $this->assertEquals('', $entity->name);
        $this->assertEquals('', $entity->getEmail());
        $this->assertEquals('', $entity->email);
    }


    /**
     * Test set email property
     *
     * @covers Content::$attributes
     * @covers Entity::__get()
     * @covers Content::__get()
     * @covers Entity::__set()
     * @covers Content::__set()
     */
    public function testSetEmail()
    {
        $entity = new Content();
        $entity->email = 'hi@roysivan.com';
        $this->assertEquals('hi@roysivan.com', $entity->email);
    }

    /**
     * Test getEmail method
     *
     * @covers Content::getEmail()
     */
    public function testGetEmailMethod()
    {
        $entity = new Content();
        $entity->email = 'hi@roysivan.com';
        $this->assertEquals('hi@roysivan.com', $entity->getEmail());
    }

    /**
     * Test setEmail method
     *
     * @covers Content::setEmail()
     */
    public function testSetEmailMethod()
    {
        $entity = new Content();
        $entity->setEmail('hi@roysivan.com');
        $this->assertEquals('hi@roysivan.com', $entity->getEmail());
        $this->assertEquals($entity->email, $entity->getEmail());
    }

    /**
     * Test getName method
     *
     * @covers Content::getEmail()
     */
    public function testGetNameMethod()
    {
        $entity = new Content();
        $entity->name = 'roy';
        $this->assertEquals('roy', $entity->getName());
    }

    /**
     * Test setName method
     *
     * @covers Content::setEmail()
     */
    public function testSetNameMethod()
    {
        $entity = new Content();
        $entity->setName('roy');
        $this->assertEquals('roy', $entity->getName());
        $this->assertEquals($entity->name, $entity->getName());
    }


    /**
     * Test setting up submitter for name/email when making array
     *
     * @covers Content::setSubmitter()
     * @covers Entity::toArray()
     * @covers Content::toArray()
     */
    public function testEmailToSubmitter()
    {
        $entity = new Content();
        $entity->email = 'hi@roysivan.com';
        $entityArray = $entity->toArray();
        $this->assertArrayHasKey('submitter', $entityArray);
        $this->assertArrayHasKey('email', $entityArray);
        $submitter = EmailSender::fromArray($entityArray['submitter']);

        $this->assertArrayHasKey('email', $submitter->toArray());
        $this->assertEquals('hi@roysivan.com', $submitter->email);
    }

    /**
     * Test set email property
     *
     * @covers Content::$attributes
     * @covers Entity::__get()
     * @covers Content::__get()
     * @covers Entity::__set()
     * @covers Content::__set()
     */
    public function testSetName()
    {
        $entity = new Content();
        $entity->name = 'Roy Sivan';
        $this->assertEquals('Roy Sivan', $entity->name);

        $entityArray = $entity->toArray();
        $this->assertEquals('Roy Sivan', $entityArray['name']);
        $submitter = EmailSender::fromArray($entityArray['submitter']);

        $this->assertEquals('Roy Sivan', $submitter->name);
    }

    /**
     * Test setting up submitter for name/email when making array
     *
     * @covers Content::setSubmitter()
     * @covers Entity::toArray()
     * @covers Content::toArray()
     */
    public function testNameToSubmitter()
    {
        $entity = new Content();
        $entity->name = 'Roy Sivan';
        $entity->email = 'hi@roysivan.com';
        $entityArray = $entity->toArray();
        $this->assertTrue(is_array($entityArray));
        $this->assertArrayHasKey('submitter', $entityArray);

        $submitter = EmailSender::fromArray($entityArray['submitter']);
        $this->assertArrayHasKey('name', $entityArray);
        $this->assertEquals('Roy Sivan', $entityArray['name']);
        $this->assertEquals('Roy Sivan', $submitter->name);
    }

    /**
     * Test setting up submitter for name/email when making array
     *
     * @covers Content::setSubmitter()
     * @covers Entity::toArray()
     * @covers Content::toArray()
     */
    public function testNameAndEmailToSubmitter()
    {
        $entity = new Content();
        $entity->name = 'Roy Sivan';
        $entity->email = 'hi@roysivan.com';
        $entityArray = $entity->toArray();
        $this->assertTrue(is_array($entityArray));
        $this->assertArrayHasKey('submitter', $entityArray);

        $submitter = EmailSender::fromArray($entityArray['submitter']);
        $this->assertArrayHasKey('name', $entityArray);
        $this->assertEquals('Roy Sivan', $entityArray['name']);
        $this->assertEquals('Roy Sivan', $submitter->name);

        $this->assertArrayHasKey('email', $entityArray);
        $this->assertEquals('hi@roysivan.com', $entityArray['email']);
        $this->assertEquals('hi@roysivan.com', $submitter->email);
    }

    /**
     *
     * @covers Entity::toArray()
     * @covers Content::toArray()
     * @covers Entity::getAllowedAttributes()
     * @covers Content::getAllowedAttributes()
     * @covers Entity::__get()
     * @covers Content::__get()
     * @covers Entity::__set()
     * @covers Content::__set()
     */
    public function testFromArray()
    {
        $expect = $this->contentRequestArgs();
        $entity = Content::fromArray($expect);
        $this->assertFalse(is_array($entity));

        $looped = false;
        foreach ($expect as $key => $value) {
            $looped = true;
            if ('submitter' !== $key) {
                $this->assertSame($value, $entity->$key);
            } else {
                $this->assertSame($value, $entity->getSubmitter()->toArray());
            }
        }
        $this->assertTrue($looped);
    }


    /**
     * Test submitter details
     *
     * @group contentSubmitter
     * @covers Content::getSubmitter()
     * @covers Content::fromArray()
     */
    public function testGetSubmitterWithValues()
    {
        $args = $this->contentRequestArgs();
        $expect = $args['submitter'];
        $entity = Content::fromArray($args);
        $submitter = $entity->getSubmitter();

        $submitterArray = $submitter->toArray();
        $this->assertSame($expect, $submitterArray);
        $this->assertSame($expect['email'], $submitterArray['email']);
        $this->assertSame($expect['name'], $submitterArray['name']);
    }

    /**
     * Test submitter translates correctly when converting to array
     *
     * @group contentSubmitter
     * @covers Content::getSubmitter()
     * @covers Content::fromArray()
     */
    public function testSubmitterWithToArray()
    {
        $entity = Content::fromArray($this->contentRequestArgs());
        $entityArray = $entity->toArray();
        $this->assertTrue(is_array($entityArray));
        $this->assertArrayHasKey('submitter', $entityArray);
        $this->assertArrayHasKey('email', $entityArray);
        $this->assertArrayHasKey('name', $entityArray);
        $this->assertEquals($entity->name, $entityArray['name']);
        $this->assertEquals($entity->email, $entityArray['email']);
        $submitter = EmailSender::fromArray($entityArray['submitter']);
        $this->assertEquals($entity->getSubmitter(), $submitter);
        $this->assertEquals($entity->getSubmitter()->toArray(), $entityArray['submitter']);
    }
}