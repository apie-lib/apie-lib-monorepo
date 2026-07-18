<?php
namespace Apie\Core\Translator\ValueObjects;

use Apie\Core\Attributes\Description;
use Apie\Core\Attributes\ExampleValue;
use Apie\Core\Lists\IdentifierList;
use Apie\Core\ValueObjects\Interfaces\StringValueObjectInterface;
use Apie\Core\ValueObjects\Interfaces\ValueObjectInterface;
use Apie\Core\ValueObjects\Utils;
use ReflectionClass;

#[Description('Menu header translations. Keys reflect the tree structure of the menu')]
#[ExampleValue('apie.menu.header.root.test.authenticated')]
class MenuHeader extends AbstractTranslation
{
    protected const MIDDLE_REGEX = 'menu\.header\.[^.]+(?:\.[^.]+)*';
}