<?php
namespace Apie\IntegrationTests\Requests;

use Apie\Core\Identifiers\SnakeCaseSlug;

interface WebdavTestRequestInterface extends TestRequestInterface
{
    public function getTestName(): SnakeCaseSlug;
    public function getExpectedStatusCode(): int;

}
