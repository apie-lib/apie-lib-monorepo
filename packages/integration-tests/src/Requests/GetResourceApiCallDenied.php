<?php
namespace Apie\IntegrationTests\Requests;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GetResourceApiCallDenied extends GetResourceApiCall
{
    public function verifyValidResponse(ResponseInterface $response): void
    {
        if ($this->faked) {
            TestCase::assertContains($response->getStatusCode(), [200, 403]);
        } else {
            TestCase::assertEquals(403, $response->getStatusCode());
        }
    }
}
