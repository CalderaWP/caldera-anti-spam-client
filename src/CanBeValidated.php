<?php


namespace calderawp\AntiSpamClient;

use Awurth\SlimValidation\ValidatorInterface;

interface CanBeValidated extends HasRules
{
    /**
     * @return ValidatorInterface
     */
    public function validate();

    /**
     * @return bool
     */
    public function isValid();
}
