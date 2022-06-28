<?php
namespace Apie\DoctrineEntityConverter\Mediators;

use Apie\Core\Dto\DtoInterface;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\Method;
use ReflectionProperty;

class GeneratedCode {
    private ClassType $classType;
    private Method $createFrom;
    private string $createFromCode = PHP_EOL;

    private Method $inject;
    private string $injectCode = PHP_EOL;

    public function __construct(string $className, string $originalClassName)
    {
        $this->classType = new ClassType($className);
        $this->classType->addImplement(DtoInterface::class);
        $this->classType->addMethod('__construct')->setPrivate(true);

        $this->createFrom = $this->classType->addMethod('createFrom')->setStatic(true)->setPublic(true);
        $this->createFrom->addParameter('input')->setType($originalClassName);
        $this->createFrom->setBody('$instance = new self();' . PHP_EOL . 'return $instance');

        $this->inject = $this->classType->addMethod('inject')->setPublic(true);
        $this->inject->addParameter('instance')->setType($originalClassName);
        $this->inject->addParameter('property')->setType(ReflectionProperty::class);
    }

    public function addCreateFromCode(string $code)
    {
        $this->createFromCode .= PHP_EOL . $code;
        $this->createFrom->setBody('$instance = new self();' . $this->createFromCode . 'return $instance');
    }

    public function addInjectCode(string $code)
    {
        $this->createInjectCode .= PHP_EOL . $code;
        $this->inject->setBody('$instance = new self();' . $this->injectCode . 'return $instance');
    }

    public function toCode(): string
    {
        return (string) $this->classType;
    }
}