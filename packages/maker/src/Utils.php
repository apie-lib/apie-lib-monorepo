<?php
namespace Apie\Maker;

use Apie\Common\ValueObjects\EntityNamespace;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use Nette\PhpGenerator\Property;

final class Utils
{
    /**
     * @codeCoverageIgnore
     */
    private function __construct()
    {
    }

    public static function addOrGetNamespace(PhpFile $phpFile, EntityNamespace $namespace): PhpNamespace
    {
        $ns = rtrim((string) $namespace, '\\');
        return $phpFile->getNamespaces()[$ns] ?? $phpFile->addNamespace($ns);
    }

    public static function addUseStatements(PhpNamespace $phpNamespace, string... $useStatements): void
    {
        $uses = $phpNamespace->getUses();
        foreach ($useStatements as $useStatement) {
            if (!in_array($useStatement, $uses)) {
                $phpNamespace->addUse($useStatement);
                $uses[] = $useStatement;
            }
        }
    }

    public static function searchOrAddProperty(ClassType $classType, string $propertyName, bool $noPromotion, bool $nullDefault = false): Property|PromotedParameter
    {
        if ($classType->hasProperty($propertyName)) {
            return $classType->getProperty($propertyName);
        }
        if (!$classType->hasMethod('__construct')) {
            $classType->addMethod('__construct');
        }
        $method = $classType->getMethod('__construct');
        if ($method->hasParameter($propertyName)) {
            $parameter = $method->getParameter($propertyName);
            if ($parameter instanceof PromotedParameter) {
                return $parameter;
            }
            return $classType->addProperty($propertyName)->setPrivate();
        }
        if ($noPromotion) {
            return $classType->addProperty($propertyName)->setPrivate();
        }
        $prop = $method->addPromotedParameter($propertyName)->setPrivate();
        if ($nullDefault) {
            $prop->setDefaultValue(null);
        }
        return $prop;
    }
}
