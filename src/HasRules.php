<?php


namespace calderawp\AntiSpamClient;

use calderawp\interop\Interfaces\Arrayable;

/**
 * Interface HasRules
 *
 * @package calderawp\AntiSpamClient
 */
interface HasRules extends Arrayable
{

    /***
     * Get validation rules
     *
     * @return array[V]
     */
    public function rules();
}
