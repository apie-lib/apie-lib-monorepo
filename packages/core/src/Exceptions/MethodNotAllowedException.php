<?php


namespace Apie\Core\Exceptions;

/**
 * Exception thrown when a wrong HTTP method is chosen.
 */
class MethodNotAllowedException extends ApieException implements LocalizationableException
{
    /**
     * @var string
     */
    private $method;

    public function __construct(string $method)
    {
        $this->method = $method;
        parent::__construct(405, "Resource has no " . $method . " support");
    }

    public function getI18n(): LocalizationInfo
    {
        return new LocalizationInfo(
            'general.method_not_allowed',
            [
                'method' => $this->method,
            ]
        );
    }
}
