<?php
namespace Apie\IntegrationTests\Graphql;

use Apie\Core\BoundedContext\BoundedContextId;
use Apie\Core\Entities\EntityInterface;
use Apie\Core\FileStorage\StoredFile;
use GuzzleHttp\Psr7\MultipartStream;
use Nyholm\Psr7\ServerRequest;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;

/**
 * @see https://github.com/jaydenseric/graphql-multipart-request-spec
 */
class GraphqlWithFileUpload extends GraphqlProvider
{
    /**
     * @param array<string, mixed> $graphQlQuery
     * @param array<string, mixed> $expectedResponse
     * @param array<int, EntityInterface> $entities
     * @param array<string|int, UploadedFileInterface> $uploadedFiles
     */
    public function __construct(
        BoundedContextId $boundedContextId,
        array $graphQlQuery,
        array $expectedResponse,
        array $entities = [],
        protected array $uploadedFiles = []
    ) {
        parent::__construct(
            $boundedContextId,
            $graphQlQuery,
            $expectedResponse,
            $entities
        );
    }

    public function getRequest(): ServerRequestInterface
    {
        $map = [];
        $files = [];
        $uploadedFiles = [];
        foreach ($this->uploadedFiles as $index => $file) {
            $fileIndex = count($files);
            $map[$fileIndex] = [$index];
            $files[] = $file;
        }
        $multipart = new MultipartStream([
            [
                'name'     => 'operations',
                'contents' => json_encode($this->graphQlQuery),
            ],
            [
                'name'     => 'map',
                'contents' => json_encode($map),
            ],
            ...array_map(
                function (UploadedFileInterface $file, int|string $index) {
                    if (!$file instanceof StoredFile) {
                        $file = StoredFile::createFromUploadedFile($file);
                    }
                    return [
                        'name' => (string) $index,
                        'filename' => $file->getClientFilename(),
                        'headers' => [
                            'Content-Type' => $file->getClientMediaType(),
                        ],
                        'contents' => $file->getContent(),
                    ];
                },
                $this->uploadedFiles,
                array_keys($this->uploadedFiles)
            )
        ]);
        $request = new ServerRequest(
            'POST',
            'http://localhost/' . $this->boundedContextId . '/graphql',
            [
                'Content-Type'   => 'multipart/form-data; boundary=' . $multipart->getBoundary(),
                'Content-Length' => $multipart->getSize(),
            ],
            $multipart
        );
        $parsedBody = [
            'operations' => json_encode($this->graphQlQuery),
            'map' => json_encode($map),
        ];
        $request = $request->withParsedBody($parsedBody)
                         ->withUploadedFiles($files);
        return $request;
    }
}
