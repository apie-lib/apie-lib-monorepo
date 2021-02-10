<?php


namespace Apie\ObjectAccessNormalizer\Errors;

use Apie\ObjectAccessNormalizer\Exceptions\LocalizationInfo;
use Throwable;

final class ErrorBagField
{
    /**
     * @var string
     */
    private $message;

    /**
     * @var LocalizationInfo|null
     */
    private $localizationInfo;

    /**
     * @var Throwable|null
     */
    private $source;

    public function __construct(string $message, ?LocalizationInfo $localizationInfo = null, ?Throwable $source = null)
    {
        $this->message = $message;
        $this->localizationInfo = $localizationInfo;
        $this->source = $source;
    }

    /**
     * Get the message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get the localization info.
     *
     * @return LocalizationInfo|null
     */
    public function getLocalizationInfo(): ?LocalizationInfo
    {
        return $this->localizationInfo;
    }

    /**
     * Get the source.
     *
     * @return Throwable|null
     */
    public function getSource(): ?Throwable
    {
        return $this->source;
    }
}
