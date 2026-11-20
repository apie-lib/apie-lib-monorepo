<?php

namespace Apie\Tests\ExchangeRate;

use Apie\ExchangeRate\ExchangeRateService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

class ExchangeRateServiceTest extends TestCase
{
    #[Test]
    public function it_returns_the_rate_for_two_currencies(): void
    {
        $client = new MockHttpClient(function (string $method, string $url): MockResponse {
            self::assertSame('GET', $method);
            self::assertSame(
                'https://api.frankfurter.app/latest?from=USD&to=EUR',
                $url
            );

            return new MockResponse(json_encode(['rates' => ['EUR' => 0.92]]));
        });

        $service = new ExchangeRateService($client);

        self::assertSame(0.92, $service->calculateConversionRate('usd', 'eur'));
    }
}
