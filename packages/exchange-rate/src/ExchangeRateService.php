<?php
namespace Apie\ExchangeRate;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private const API_URL = 'https://api.frankfurter.app/latest';

    public function __construct(private ?HttpClientInterface $client = null)
    {
        $this->client ??= HttpClient::create();
    }

    public function calculateConversionRate(string $fromCurrency, string $toCurrency): float
    {
        $fromCurrency = $this->normalizeCurrency($fromCurrency);
        $toCurrency = $this->normalizeCurrency($toCurrency);

        if ($fromCurrency === $toCurrency) {
            return 1.0;
        }

        $response = $this->client->request('GET', self::API_URL, [
            'query' => [
                'from' => $fromCurrency,
                'to' => $toCurrency,
            ],
        ]);
        $data = $response->toArray();
        $rate = $data['rates'][$toCurrency] ?? null;

        if (!is_int($rate) && !is_float($rate)) {
            throw new \RuntimeException(sprintf(
                'The exchange rate API did not return a rate for %s to %s.',
                $fromCurrency,
                $toCurrency
            ));
        }

        return (float) $rate;
    }

    private function normalizeCurrency(string $currency): string
    {
        $currency = strtoupper(trim($currency));

        if (!preg_match('/^[A-Z]{3}$/', $currency)) {
            throw new \InvalidArgumentException('Currency codes must contain exactly three letters.');
        }

        return $currency;
    }
}
