<?php
namespace Apie\Maker\ValueObjects;

use Apie\Core\Identifiers\CamelCaseSlug;
use Apie\Serializer\Exceptions\ValidationException;
use LogicException;

final class PropertyDefinitionName extends CamelCaseSlug
{
    public static function validate(string $input): void
    {
        parent::validate($input);
        if (strtolower($input) === 'id') {
            throw ValidationException::createFromArray(['' => new LogicException('The name "id" is already reserved')]);
        }
    }
}
