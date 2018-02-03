<?php


namespace calderawp\AntiSpamClient\Tests;

use calderawp\AntiSpamClient\Content;
use calderawp\AntiSpamClient\ContentController;

/**
 * Class SpamMockController
 *
 * Mock controller that reports all valid requests as spam
 *
 * @package calderawp\AntiSpamClient\Tests
 */
class SpamMockController extends ContentController
{

    /** @inheritdoc */
    public function entityIsNotSpam(Content $entity)
    {
        return false;
    }
}
