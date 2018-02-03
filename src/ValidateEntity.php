<?php


namespace calderawp\AntiSpamClient;

use Awurth\SlimValidation\Validator;

class ValidateEntity
{

    /**
     * Validate an entity
     *
     * @param HasRules $entity
     * @return Validator
     */
    public static function validate(HasRules $entity)
    {
        $validator = new Validator();
        $validator->array($entity->toArray(), $entity->rules());
        return $validator;
    }
}
