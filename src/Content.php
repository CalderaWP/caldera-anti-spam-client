<?php


namespace calderawp\AntiSpamClient;

use Awurth\SlimValidation\ValidatorInterface;
use calderawp\interop\Entities\EmailSender;
use calderawp\interop\Interfaces\Interoperable;
use calderawp\interop\Traits\CanRecursivelyCastArray;
use Psr\Http\Message\RequestInterface;
use Respect\Validation\Validator as V;
use Respect\Validation\Validator;

class Content extends \calderawp\interop\Entities\Entity implements CanBeValidated
{
    use CanRecursivelyCastArray;
    /**
     * @var EmailSender
     */
    protected $submitter;

    /**
     * The URL of page content is submitted on
     *
     * @var string
     */
    protected $url;

    /**
     * The URL of site content is submitted on
     *
     * @var string
     */
    protected $site_url;

    /**
     * The type of content to scan
     *
     * @var string
     */
    protected $type;

    /**
     * The IP of content submitter
     *
     * @var string
     */
    protected $ip;

    /**
     * The user agent of content submitter
     *
     * @var string
     */
    protected $user_agent;

    /**
     * The referrer of content submitter
     *
     * @var string
     */
    protected $referrer;

    /**
     * Mark as test
     *
     * @var bool
     */
    protected $is_test;

    /**
     * Language code for submission
     *
     * @var string
     */
    protected $lang;

    /**
     * Is entity valid?
     *
     * @var bool
     */
    protected $valid;

    /**
     * Get submitter name
     *
     * @return string
     */
    public function getName()
    {
        return $this->getSubmitter()->name ? $this->getSubmitter()->name : '';
    }

    /**
     * Set submitter name
     *
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->getSubmitter()->name = $name;
        return $this;
    }

    /**
     * Get submitter email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->getSubmitter()->email ? $this->getSubmitter()->email : '';
    }

    /**
     * Set submitter email
     *
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->getSubmitter()->email = $email;
        return $this;
    }

    /**
     * Get email sender.
     *
     * @return EmailSender
     */
    public function getSubmitter()
    {
        if (!is_object($this->submitter)) {
            $this->submitter = new EmailSender();
        }

        return $this->submitter;
    }

    /** @inheritdoc */
    public function toArray()
    {
        $array = parent::toArray();
        $array['email'] = $this->getEmail();
        $array['name'] = $this->getName();
        $array[ 'submitter' ] = $this->getSubmitter()->toArray();
        unset($array['id']);
        unset($array['valid']);
        return $array;
    }

    /** @inheritdoc */
    public static function fromArray(array $items)
    {
        /** @var Content $obj */
        $obj = parent::fromArray($items);
        if (!empty($items['name'])) {
            $obj->setName($items['name']);
        }

        if (!empty($items['email'])) {
            $obj->setEmail($items['email']);
        }

        return $obj;
    }

    /** @inheritdoc */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'name':
                $this->setName($value);
                break;
            case 'email':
                $this->setEmail($value);
                break;
            case 'submitter':
                if (is_array($value) || is_a($value, 'stdClass')) {
                    $value = (array)$value;
                    if (isset($value['name'])) {
                        $this->setName($value['name']);
                    }
                    if (isset($value['email'])) {
                        $this->setEmail($value['email']);
                    }
                } else {
                    $this->submitter = $value;
                }

                break;
            default:
                parent::__set($name, $value);
                break;
        }
    }

    /** @inheritdoc */
    public function __get($name)
    {
        switch ($name) {
            case 'name':
                return $this->getName();
                break;
            case 'email':
                return $this->getEmail();
                break;
            case 'submitter':
                return $this->getSubmitter();
                break;
            default:
                return parent::__get($name);
                break;
        }
        return null;
    }

    /**
     * Get a value from entity with default fallbackl
     *
     * @param $name
     * @param null $default
     * @return EmailSender|null|string
     */
    public function get($name, $default = null)
    {
        $value = $this->__get($name);
        return ! is_null($value) ? $value : $default;
    }


    /**
     * Create HTTP request object for dispatching by supplied client from this entity
     *
     * @param Client $client
     * @return Request
     */
    public function toRequest(Client $client)
    {
        return new Request(
            'POST',
            $client->getEndpointUrl('content'),
            $client->createHeaders(),
            \GuzzleHttp\json_encode($this)
        );
    }

    /**
     * Create entity from Request
     *
     * @param RequestInterface $request
     * @return Content|static
     */
    public static function fromRequest(RequestInterface $request)
    {
        return  Content::fromArray(
            self::arrayCastRecursiveStatic(
                (array) \GuzzleHttp\json_decode($request->getBody())
            )
        );
    }

    /** @inheritdoc */
    public function validate()
    {
        return ValidateEntity::validate($this);
    }

    /** @inheritdoc */
    public function isValid()
    {
        return $this->validate()->isValid();
    }

    /***
     * @return array[V]
     */
    public function rules()
    {
        return [
            'url' => V::notEmpty()->addRule(V::url()),
            'site_url' => $this->requiredUrl(),
            'ip' =>V::notEmpty()->addRule(V::ip()),
            'user_agent' => V::stringType()->addRule(V::notEmpty()),
            'referrer' => $this->requiredUrl(),
            'type' => V::in(['contact-form', 'signup', 'message', 'blog-post', 'reply', 'comment']),
            'email' => V::notEmpty()->addRule(V::email()),
            'name' => V::notEmpty()->addRule(V::stringType()),
            'fail' => V::optional(V::boolType()),
            'lang' => V::stringType(),
            //these settings make it allways pass
            'is_test' => V::optional(V::boolType()),
        ];
    }



    /**
     * @return V
     */
    protected function requiredUrl()
    {
        return V::notEmpty()->addRule(v::url());
    }
}
