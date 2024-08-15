<?php
namespace Apie\Maker\BoundedContext\Services;

use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Core\Other\FileReaderInterface;
use Apie\Core\Other\FileWriterInterface;
use Apie\Core\ValueObjects\Utils;
use Apie\Maker\BoundedContext\Entities\PropertyDefinition;
use Apie\Maker\BoundedContext\Interfaces\CodeWriterConfigurationInterface;
use Apie\Maker\BoundedContext\Resources\BoundedContextDefinition;
use Apie\Maker\BoundedContext\Resources\ResourceDefinition;
use Apie\Maker\Enums\NullableOption;
use Apie\Maker\Enums\OverwriteStrategy;
use Apie\Maker\Enums\PrimitiveType;
use Nette\InvalidArgumentException;
use Nette\InvalidStateException;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use Nette\PhpGenerator\Parameter;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PhpNamespace;
use Nette\PhpGenerator\PromotedParameter;
use Nette\PhpGenerator\Property;
use Nette\PhpGenerator\PsrPrinter;
use ReflectionClass;

final class CodeWriter
{
    public function __construct(private readonly FileWriterInterface&FileReaderInterface $fileWriter)
    {
    }
    public function startWriting(CodeWriterConfigurationInterface $log): void
    {
        if ($log->getOverwriteStrategy() === OverwriteStrategy::Reset) {
            $this->fileWriter->clearPath($log->getTargetPath());
        }
    }

    public function writeIdentifier(
        CodeWriterConfigurationInterface $log,
        ResourceDefinition $resourceDefinition,
        BoundedContextDefinition $boundedContextDefinition
    ): void {
        $boundedNs = $boundedContextDefinition->name->toPascalCaseSlug()->toNative();
        $targetNamespace = $log->getTargetNamespace(
            $boundedNs . '\\Identifiers'
        );
        $targetFile = $log->getTargetPath() . '/' . $boundedNs . '/Identifiers/' . $resourceDefinition->getName()->toNative() . 'Identifier.php';
        if ($log->getOverwriteStrategy() === OverwriteStrategy::Merge && $this->fileWriter->fileExists($targetFile)) {
            $file = PhpFile::fromCode($this->fileWriter->readContents($targetFile));
        } else {
            $file = new PhpFile();
        }

        $namespace = $this->addOrGetNamespace($file, $targetNamespace);
        $targetIdentifier = $log->getTargetNamespace(
            $boundedNs . '\\Resources\\' . $resourceDefinition->getName()->toNative()
        );
        $this->addOrGetUse($namespace, ReflectionClass::class);
        $this->addOrGetUse($namespace, IdentifierInterface::class);
        $this->addOrGetUse($namespace, $targetIdentifier);
        $this->addOrGetUse($namespace, $resourceDefinition->idType->value);
        $class = $this->addOrGetClass($namespace, $resourceDefinition->getName()->toNative() . 'Identifier');
        $class->setExtends($resourceDefinition->idType->value);
        $this->addOrGetImplement($class, IdentifierInterface::class);

        $method = $this->addOrGetMethod($class, 'getReferenceFor');
        $method->setStatic(true);
        $method->setBody('return new ReflectionClass(' . $resourceDefinition->getName()->toNative() . '::class);');
        $method->setReturnType(ReflectionClass::class);

        $printer = new PsrPrinter();
        $this->fileWriter->writeFile($targetFile, $printer->printFile($file));
    }

    public function writeResource(
        CodeWriterConfigurationInterface $log,
        ResourceDefinition $resourceDefinition,
        BoundedContextDefinition $boundedContextDefinition
    ): void {
        $boundedNs = $boundedContextDefinition->name->toPascalCaseSlug()->toNative();
        $targetNamespace = $log->getTargetNamespace(
            $boundedNs . '\\Resources'
        );
        $targetFile = $log->getTargetPath() . '/' . $boundedNs . '/Resources/' . $resourceDefinition->getName()->toNative() . '.php';
        if ($log->getOverwriteStrategy() === OverwriteStrategy::Merge && $this->fileWriter->fileExists($targetFile)) {
            $file = PhpFile::fromCode($this->fileWriter->readContents($targetFile));
        } else {
            $file = new PhpFile();
        }
        $namespace = $this->addOrGetNamespace($file, $targetNamespace);
        $class = $this->addOrGetClass($namespace, $resourceDefinition->getName()->toNative());
        $this->addOrGetImplement($class, EntityInterface::class);
        $this->addOrGetUse($namespace, EntityInterface::class);

        $constructorArguments = [];

        
        $idClass = $resourceDefinition->getName()->toNative() . 'Identifier';
        $targetIdentifier = $log->getTargetNamespace($boundedNs . '\\Identifiers\\' . $idClass);
        $constructorArgument = $resourceDefinition->idType->toConstructorArgument($targetIdentifier);
        if (!($constructorArgument instanceof PromotedParameter)) {
            $this->addOrGetProperty($class, 'id', $targetIdentifier);
        }
        if ($constructorArgument instanceof Parameter) {
            $constructorArguments[] = $constructorArgument;
        }
        $this->addOrgetUse($namespace, $targetIdentifier);

        $constructor = $this->addOrGetMethod($class, '__construct');
        $constructor->setParameters($constructorArguments);
        $constructor->setBody($resourceDefinition->idType->toConstructorBody($idClass));
        $constructor->setStatic(false);

        $getId = $this->addOrGetMethod($class, 'getId');
        $getId->setBody('return $this->id;');
        $getId->setReturnType($targetIdentifier);

        foreach ($resourceDefinition->getProperties() as $propertyDefinition) {
            /** @var PropertyDefinition $propertyDefinition  */
            if (!($propertyDefinition->type instanceof PrimitiveType)) {
                $this->addOrGetUse($namespace, $propertyDefinition->type->toNative());
            }
            $propertyName = $propertyDefinition->name->toNative();
            $propertyType = Utils::toString($propertyDefinition->type);
            $propertyNullable = ($propertyDefinition->nullable === NullableOption::NeverNullable || $propertyType === 'null')
                ? ''
                : '?';
            
            if ($propertyDefinition->requiredOnConstruction) {
                if ($propertyDefinition->nullable !== NullableOption::NeverNullable || $propertyType === 'null') {
                    $property = $constructor->addPromotedParameter($propertyName, null);
                } else {
                    $property = $constructor->addPromotedParameter($propertyName);
                }
                $property->setType($propertyNullable . $propertyType);
                $property->setPrivate();

                if ($class->hasProperty($propertyName)) {
                    $class->removeProperty($propertyName);
                }
            } else {
                $property = $this->addOrGetProperty($class, $propertyName, $propertyNullable . $propertyType, null);
                $property->setInitialized($propertyDefinition->nullable !== NullableOption::NeverNullable || $propertyType === 'null');
            }
            
            if ($propertyDefinition->writable) {
                $setter = $this->addOrGetMethod($class, 'set' . ucfirst($propertyName));
                $parameter = new Parameter($propertyName);
                if ($propertyDefinition->nullable === NullableOption::AlwaysNull) {
                    $parameter->setType($propertyNullable . $propertyType);
                } else {
                    $parameter->setType($propertyType);
                }
                $parameters = array_keys($setter->getParameters());
                if (empty($parameters)) {
                    $parameters[] = $parameter;
                } else {
                    $parameters[count($parameters) - 1] = $parameter;
                }
                $setter->setParameters($parameters);
                $setter->setBody('$this->' . $propertyName . ' = $' . $propertyName . ';');
            }

            if ($propertyDefinition->readable) {
                $getter = $this->addOrGetMethod($class, 'get' . ucfirst($propertyName));
                $getter->setReturnType($propertyNullable . $propertyType);
                $body = '';
                if ($propertyNullable === '' && !$propertyDefinition->requiredOnConstruction) {
                    $body .= 'if (!isset($this->' . $propertyName . ')) {' . PHP_EOL;
                    $body .= '    throw new \LogicException("Property \"' . $propertyName . '\" is not set yet!");' . PHP_EOL;
                    $body .= '}' . PHP_EOL;
                }
                $getter->setBody($body . 'return $this->' . $propertyName . ';');
            }
        }
        // TODO sort constructor arguments in without default value and with default value
        $constructorArguments = $constructor->getParameters();
        $withDefaultValues = [];
        $withoutDefaultValues = [];
        foreach ($constructorArguments as $constructorArgument) {
            if ($constructorArgument->hasDefaultValue()) {
                $withDefaultValues[] = $constructorArgument;
            } else {
                $withoutDefaultValues[] = $constructorArgument;
            }
        }
        $constructor->setParameters([...$withoutDefaultValues, ...$withDefaultValues]);

        $printer = new PsrPrinter();
        $this->fileWriter->writeFile($targetFile, $printer->printFile($file));
    }

    private function addOrGetNamespace(PhpFile $file, string $namespaceName): PhpNamespace
    {
        $namespaces = $file->getNamespaces();
        $namespaceNameCmp = strtolower($namespaceName);
        foreach ($namespaces as $namespace) {
            if (strtolower($namespace->getName()) === $namespaceNameCmp) {
                return $namespace;
            }
        }
        return $file->addNamespace($namespaceName);
    }

    private function addOrGetUse(PhpNamespace $namespace, string $use): void
    {
        try {
            $namespace->addUse($use);
        } catch (InvalidStateException) {
        }
    }

    private function addOrGetClass(PhpNamespace $namespace, string $className): ClassType
    {
        try {
            $classType = $namespace->getClass($className);
            if ($classType instanceof ClassType) {
                return $classType;
            }
            $namespace->removeClass($className);
            return $namespace->addClass($className);
        } catch (InvalidArgumentException) {
            return $namespace->addClass($className);
        }
    }

    private function addOrGetImplement(ClassType $class, string $interfaceName): void
    {
        if (!in_array($interfaceName, $class->getImplements())) {
            $class->addImplement($interfaceName);
        }
    }

    private function addOrGetMethod(ClassType $class, string $methodName): Method
    {
        $methodNameCmp = strtolower($methodName);
        foreach ($class->getMethods() as $method) {
            if (strtolower($method->getName()) === $methodNameCmp) {
                return $method;
            }
        }
        return $class->addMethod($methodName);
    }

    private function addOrGetProperty(
        ClassType $class,
        string $propertyName,
        string $propertyType = 'mixed',
        mixed $defaultValue = null
    ): Property {
        $type = $class->addProperty($propertyName, value: $defaultValue, overwrite: true);
        $type->setInitialized($defaultValue !== null || count(func_get_args()) > 3);
        $type->setType($propertyType);
        $type->setPrivate();
        return $type;
    }
}
