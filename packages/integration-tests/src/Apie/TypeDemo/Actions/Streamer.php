<?php
namespace Apie\IntegrationTests\Apie\TypeDemo\Actions;

use Apie\Core\Actions\ActionResponseStatus;
use Apie\Core\Attributes\ActionResponseList;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class Streamer
{
    #[ActionResponseList(ActionResponseStatus::DELETED)]
    public function emptyResponse(string $ignored): ResponseInterface
    {
        return new Response(204, body: $ignored);
    }
}
