<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Lists\StringList;
use Apie\Core\Translator\ValueObjects\AbstractTranslation;

class Translation
{
    public function translate(StringList $list): AbstractTranslation
    {
        return AbstractTranslation::fromNative($list->join('.'));
    }
}