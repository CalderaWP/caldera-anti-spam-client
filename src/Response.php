<?php


namespace calderawp\AntiSpamClient;

use calderawp\interop\Interfaces\Arrayable;
use Psr\Http\Message\ResponseInterface;

class Response extends \GuzzleHttp\Psr7\Response implements ResponseInterface, Arrayable
{

    /**
     * Is this a spam response?
     *
     * @return bool
     */
    public function isSpam()
    {
        $arrayed = $this->toArray();
        return isset($arrayed[ Client::VALIDKEY ]) && $arrayed[ Client::VALIDKEY ] ? true : false;
    }

    /**
     * Return a response indicating spam was detected
     *
     * @return static
     */
    public static function isSpamResponse()
    {
        return new static(
            200,
            self::defaultHeaders(),
            self::createResponseBody(false)
        );
    }

    /**
     * Return a response indicating spam was not detected
     *
     * @return static
     */
    public static function notSpamResponse()
    {
        return new static(
            200,
            self::defaultHeaders(),
            self::createResponseBody(true)
        );
    }

    /**
     * Return a response indicating request was invalid
     *
     * @return static
     */
    public static function invalidRequestResponse(array $errors)
    {
        return new static(
            421,
            self::defaultHeaders(),
            self::createResponseBody(false, ['errors' => $errors])
        );
    }

    /**
     * Return a response indicating an unAuthorized request
     *
     * @return static
     */
    public static function unAuthorizedResponse()
    {
        return new static(
            401,
            self::defaultHeaders(),
            self::createResponseBody(
                false,
                [
                    'errors' => [
                        'authorization' => 'Unauthorized'
                    ],
                ],
                false
            )
        );
    }

    /**
     * Get the default headers
     *
     * @return array
     */
    public static function defaultHeaders()
    {
        return [
            'content-type' => 'application/json',
            'X-Hi-Roy' => 'true'
        ];
    }

    /**
     * @return string
     */
    protected static function createResponseBody($valid, array $data = [], $authorized = true)
    {
        return \GuzzleHttp\json_encode(
            array_merge(
                $data,
                [
                    'authorized' => $authorized,
                    Client::VALIDKEY => $valid
                ]
            )
        );
    }

    /** @inheritdoc */
    public function toArray()
    {
        return (array)json_decode($this->getBody(), true);
    }
}
