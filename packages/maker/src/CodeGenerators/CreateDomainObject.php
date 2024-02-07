<?php
namespace Apie\Maker\CodeGenerators;

use Apie\Common\ValueObjects\EntityNamespace;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\Identifiers\IdentifierInterface;
use Apie\Maker\Dtos\DomainObjectDto;
use Apie\Maker\Enums\IdType;
use Apie\Maker\Utils;
use LogicException;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PromotedParameter;
use Nette\PhpGenerator\PsrPrinter;
use ReflectionClass;

class CreateDomainObject
{
    /**
     * @param array<string, mixed> $boundedContexts
     */
    public function __construct(
        private readonly array $boundedContextConfig
    ) {
    }

    private function getBoundedContextConfig(DomainObjectDto $domainObjectDto): array
    {
        $boundedContextId = $domainObjectDto->boundedContextId->toNative();
        if (!isset($this->boundedContextConfig[$boundedContextId])) {
            throw new LogicException('Bounded context "' . $boundedContextId . '" not found!');
        }
        return $this->boundedContextConfig[$boundedContextId];
    }

    public function getIdentifierPath(DomainObjectDto $domainObjectDto): string
    {
        $boundedContextConfig = $this->getBoundedContextConfig($domainObjectDto);
        return $boundedContextConfig['entities_folder'] . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'Identifiers' . DIRECTORY_SEPARATOR . $domainObjectDto->name . 'Identifier.php';
    }

    public function getDomainObjectPath(DomainObjectDto $domainObjectDto): string
    {
        $boundedContextConfig = $this->getBoundedContextConfig($domainObjectDto);
        return $boundedContextConfig['entities_folder'] . DIRECTORY_SEPARATOR . $domainObjectDto->name . '.php';
    }

    public function generateDomainIdentifierCode(DomainObjectDto $domainObjectDto): string
    {
        $boundedContextConfig = $this->getBoundedContextConfig($domainObjectDto);
        $resourceNamespace = new EntityNamespace($boundedContextConfig['entities_namespace']);
        $idNamespace = $resourceNamespace->getParentNamespace()->getChildNamespace('Identifiers');
        
        $file = new PhpFile();
        if ($domainObjectDto->reuseExistingCode) {
            $targetPath = $this->getIdentifierPath($domainObjectDto);
            if (file_exists($targetPath)) {
                $file = PhpFile::fromCode(file_get_contents($targetPath));
            }
        }

        $code = Utils::addOrGetNamespace($file, $idNamespace);
        Utils::addUseStatements($code, IdentifierInterface::class, $domainObjectDto->idType->value, ReflectionClass::class);
        $classType = $code->getClasses()[$domainObjectDto->name . 'Identifier'] ?? $code->addClass($domainObjectDto->name . 'Identifier');
        $classType->setExtends($domainObjectDto->idType->value);
        if (!in_array(IdentifierInterface::class, $classType->getImplements())) {
            $classType->addImplement(IdentifierInterface::class);
        }
        if (!$classType->hasMethod('getReferenceFor')) {
            $classType->addMethod('getReferenceFor');
        }
        $classType->getMethod('getReferenceFor')
            ->setStatic(true)
            ->setBody('return new ReflectionClass(' . $domainObjectDto->name. '::class);')
            ->setReturnType(ReflectionClass::class);
        $printer = new PsrPrinter();
        return $printer->printFile($file);
    }

    public function generateDomainObjectCode(DomainObjectDto $domainObjectDto): string
    {
        $boundedContextConfig = $this->getBoundedContextConfig($domainObjectDto);
        $resourceNamespace = new EntityNamespace($boundedContextConfig['entities_namespace']);
        $idNamespace = $resourceNamespace->getParentNamespace()->getChildNamespace('Identifiers');
        $file = new PhpFile();
        if ($domainObjectDto->reuseExistingCode) {
            $targetPath = $this->getDomainObjectPath($domainObjectDto);
            if (file_exists($targetPath)) {
                $file = PhpFile::fromCode(file_get_contents($targetPath));
            }
        }
        $code = Utils::addOrGetNamespace($file, $resourceNamespace);
        $classType = $code->getClasses()[(string) $domainObjectDto->name] ?? $code->addClass((string) $domainObjectDto->name);
        if (!in_array(EntityInterface::class, $classType->getImplements())) {
            $classType->addImplement(EntityInterface::class);
        }
        $shouldInitConstructor = !$classType->hasMethod('__construct') && $domainObjectDto->idType === IdType::Integer;
        $idProperty = Utils::searchOrAddProperty($classType, 'id', $domainObjectDto->idType === IdType::Integer, $domainObjectDto->idType === IdType::Uuid);
        $idClass = $idNamespace . $domainObjectDto->name . 'Identifier';
        Utils::addUseStatements($code, $idClass);
        $idProperty->setType($idClass);
        if ($shouldInitConstructor && !$idProperty instanceof PromotedParameter) {
            // constructor is created in Utils::searchOrAddProperty....
            $classType->getMethod('__construct')
                ->addBody('$this->id = new ' . $domainObjectDto->name . 'Identifier(null);');
        }
        if (!$classType->hasMethod('getId')) {
            $classType->addMethod('getId')->setBody('return $this->id;')->setReturnType($idClass);
        }
        $printer = new PsrPrinter();
        return $printer->printFile($file);
    }
}
