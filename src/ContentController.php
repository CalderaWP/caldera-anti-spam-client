<?php


namespace calderawp\AntiSpamClient;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\RequestInterface;

abstract class ContentController
{

    /**
     * @var ContainerInterface
     */
    protected $appContainer;

    /**
     * ContentController constructor.
     * @param ContainerInterface $appContainer
     */
    public function __construct(ContainerInterface $appContainer)
    {
        $this->appContainer = $appContainer;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->appContainer->get('contentClient');
    }

    /**
     * Check content for spam
     *
     * @param RequestInterface $request
     * @return Response
     */
    public function check(RequestInterface $request)
    {
        $entity = $this->entityFromRequest($request);
        $validator = $entity->validate();
        if (! $validator->isValid()) {
            return Response::invalidRequestResponse($validator->getErrors());
        }

        if ($this->entityIsNotSpam($entity)) {
            return Response::notSpamResponse();
        }

        return Response::isSpamResponse();
    }

    protected function validateEntity(Content $entity)
    {
        $validator = $entity->validate();
        if (! $validator->isValid()) {
            return Response::invalidRequestResponse($validator->getErrors());
        }

        if ($this->entityIsNotSpam($entity)) {
            return Response::notSpamResponse();
        }

        return Response::isSpamResponse();
    }

    /**
     * Test if entity is spam
     *
     * @param Content $entity
     * @return bool
     */
    abstract public function entityIsNotSpam(Content $entity);

    /**
     * Covert entity to request
     *
     * JOSH: This method seems dumb,
     * don't remove it or change it to protected
     * or anti-spam tests will not work.
     *
     * @param RequestInterface $request
     * @return Content|static
     */
    public function entityFromRequest(RequestInterface $request)
    {
        return Content::fromRequest($request);

    }
}
