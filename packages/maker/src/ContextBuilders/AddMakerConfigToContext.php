<?php
namespace Apie\Maker\ContextBuilders;

use Apie\Core\Context\ApieContext;
use Apie\Core\ContextBuilders\ContextBuilderInterface;
use Apie\Maker\Utils;

final class AddMakerConfigToContext implements ContextBuilderInterface
{
    public function __construct(private readonly ?array $config)
    {
    }
    public function process(ApieContext $context): ApieContext
    {
        if (is_array($this->config)) {
            return $context->withContext(
                Utils::MAKER_CONFIG,
                $this->config
            );
        }
        return $context;
    }
}
