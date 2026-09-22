<?php
namespace Apie\ApieFileSystem\Virtual;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Context\ApieContext;
use Apie\Core\ContextConstants;
use Apie\Core\Datalayers\ApieDatalayer;
use Apie\Export\EntityExport;
use Apie\Export\ValueObjects\FileExtension;
use ReflectionClass;
use Symfony\Component\Mime\MimeTypes;

class ExportResourceFile implements VirtualFileInterface
{
    public function __construct(
        private readonly ApieContext $context,
        private readonly ReflectionClass $resourceName,
        private readonly FileExtension $fileExtension,
        private readonly EntityExport $exporter,
    ) {
    }

    public function getName(): string
    {
        return $this->resourceName->getShortName() . '.' . $this->fileExtension->toNative();
    }

    /**
     * @return resource
     */
    public function getContents(): mixed
    {
        $apieDatalayer = $this->context->getContext(ApieDatalayer::class);
        return $this->exporter->streamFromEntityList(
            $this->resourceName,
            $apieDatalayer->all($this->resourceName, new BoundedContextId($this->context->getContext(ContextConstants::BOUNDED_CONTEXT_ID))),
            $this->context,
            $this->getName()
        )->detach();
    }

    public function getSize(): ?int
    {
        return null;
    }

    public function getMimeType(): string
    {
        return MimeTypes::getDefault()
            ->getMimeTypes($this->fileExtension->toNative())[0] ?? 'application/octet-stream';
    }
}
