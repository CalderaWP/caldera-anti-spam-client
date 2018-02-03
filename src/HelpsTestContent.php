<?php


namespace calderawp\AntiSpamClient;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client as Guzzle;

trait HelpsTestContent
{
    /**
     * Create request args
     *
     * @param array $args Override defaults
     * @return array
     */
    protected function contentRequestArgs(array $args = []) : array
    {
        return array_merge([
            'url' => 'https://hiroy.club/hello-roy',
            'site_url' => 'https://hiroy.club',
            'type' => 'contact-form',
            'ip' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.103 Safari/537.36.', // @codingStandardsIgnoreLine
            'referrer' => 'https://www.google.com/',
            //according to docs this makes it spam
            'submitter' => [
                'name' => 'viagra-test-123',
                'email' => 'akismet-guaranteed-spam@example.com',
            ],
            'is_test' => true,
            'lang' => 'en'

        ], $args);
    }

    /**
     * @param $status
     * @param null $body
     * @return Client
     */
    protected function getClient($status, $body = null, Response $nextResponse = null)
    {
        if (401 == $status) {
            $key = 'invalid';
        } else {
            $key = '12345';
            if (! $body) {
                $body = json_encode([ Client::VALIDKEY  => true ]);
            }
        }

        if (! $nextResponse) {
            $nextResponse = [new Response($status, [], $body)];
        }

        $mock = new MockHandler($nextResponse);
        $handler = HandlerStack::create($mock);
        $client = new Guzzle(['handler' => $handler]);

        return new Client($key, 'http://mocked.xyz', $client);
    }

    /**
     * @return \calderawp\AntiSpamClient\Content|static
     */
    protected function contentEntityFactory()
    {
        $args = $this->contentRequestArgs();
        $entity = \calderawp\AntiSpamClient\Content::fromArray($args);
        return $entity;
    }
}
