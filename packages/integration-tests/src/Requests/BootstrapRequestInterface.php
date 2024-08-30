<?php
namespace Apie\IntegrationTests\Requests;

use Apie\IntegrationTests\Interfaces\TestApplicationInterface;

interface BootstrapRequestInterface
{
    public function bootstrap(TestApplicationInterface $testApplication): void;
}
