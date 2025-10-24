<?php
namespace Apie\Webdav\Controller;

use Apie\ApieFileSystem\ApieFilesystemFactory;
use Apie\Core\ContextBuilders\ContextBuilderFactory;
use Apie\Core\ContextConstants;
use Apie\Webdav\Dav\ApieDirectory;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sabre\DAV\Browser\Plugin;
use Sabre\DAV\Server;
use Sabre\HTTP\Request as SabreRequest;
use Sabre\HTTP\Response as HTTPResponse;

/**
 * PSR-15 compatible WebDAV controller.
 */
class WebdavController
{
    public function __construct(
        private readonly ApieFilesystemFactory $apieFilesystemFactory,
        private readonly ContextBuilderFactory $contextBuilderFactory,
        private readonly bool $debug = false,
    ) {
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        $apieContext = $this->contextBuilderFactory
            ->createFromRequest($request, [ContextConstants::WEBDAV => true, ContextConstants::RAW_CONTENTS => []]);
        $filesystem = $this->apieFilesystemFactory->create($apieContext);
        $server = new Server(new ApieDirectory($filesystem->rootFolder));
        $server->debugExceptions = $this->debug;
        $server->setBaseUri('/webdav');
        $server->addPlugin(new Plugin()); // Optional browser UI
        $sabreRequest = new SabreRequest(
            $request->getMethod(),
            (string) $request->getUri()->getPath(),
            $request->getHeaders(),
            (string) $request->getBody()
        );

        $sabreRequest->setHttpVersion($request->getProtocolVersion());
        $sabreRequest->setRawServerData($request->getServerParams());
        $sabreResponse = new HTTPResponse();

        $server->httpRequest = $sabreRequest;
        $server->httpResponse = $sabreResponse;
        try {
            ob_start();
            $server->start();
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $server->emit('exception', [$e]);
        } finally {
            $body = ob_get_clean();
        }

        $psrResponse = new Response(
            $sabreResponse->getStatus(),
            $sabreResponse->getHeaders(),
            $body
        );

        return $psrResponse;
    }
}
