<?php
namespace Apie\IntegrationTests\Concerns;

use Apie\IntegrationTests\FixtureUtils;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

trait TestsDefaultWebdavXml
{
    public function shouldDoResponseValidation(): bool
    {
        return true;
    }


    public function verifyValidResponse(ResponseInterface $response): void
    {
        $body = $response->getBody();
        // remove <s:stacktrace> because it is not deterministic because of full file path
        $body = preg_replace('/<s:stacktrace>.*?<\/s:stacktrace>/s', '', (string) $body);
        // remove <s:file> because they are not deterministic because of full file path
        $body = preg_replace('/<s:file>.*?<\/s:file>/s', '', $body);
        // remove <s:line> because they are not deterministic because of full file path
        $body = preg_replace('/<s:line>.*?<\/s:line>/s', '', $body);
        $body = str_replace('<s:code>0</s:code>', '', $body);

        if (!$this->faked) {
            TestCase::assertEquals($this->getExpectedStatusCode(), $response->getStatusCode(), 'Body is ' . substr($response->getBody(), 0, 400));
            $fixtureFile = __DIR__ . '/../../fixtures/Webdav/' . $this->getTestName() . '.xml';
        
            if (FixtureUtils::shouldOverwriteWebdavFixtures() || !file_exists($fixtureFile)) {
                $xmlString = $body;
                $dom = new \DOMDocument();
                $dom->preserveWhiteSpace = false;
                $dom->formatOutput = true;
                $dom->loadXML($xmlString);
                file_put_contents($fixtureFile, $dom->saveXML());
            }
            TestCase::assertXmlStringEqualsXmlFile($fixtureFile, $body);
        }
        TestCase::assertEquals('application/xml; charset=utf-8', $response->getHeaderLine('Content-Type'));
    }
}
