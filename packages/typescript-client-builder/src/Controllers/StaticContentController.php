<?php
namespace Apie\TypescriptClientBuilder\Controllers;

use Nyholm\Psr7\Response;
use Nyholm\Psr7\Stream;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

class StaticContentController
{
    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $filename = $request->getAttribute('filename');
        $localFilepath = $request->getAttribute('localFilepath');
        $filePath = $localFilepath . '/' . $filename;
        $stream = Stream::create(fopen($filePath, 'rb'));
        $mimeType = mime_content_type($filePath);
        if ($mimeType === false) {
            $mimeType = 'application/octet-stream';
        }
        return new Response(
            200,
            ['Content-Type' => $mimeType],
            $stream
        );
    }
}
