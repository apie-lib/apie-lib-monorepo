<?php
namespace Apie\RestApi\Actions;

use Apie\Core\Actions\ActionInterface;
use Apie\Core\Context\ApieContext;

/**
 * Action to create a new object.
 */
class GetListAction implements ActionInterface
{
    /**
     * @param array<string|int, mixed> $rawContents
     */
    public function __invoke(ApieContext $context, array $rawContents): mixed
    {
        return [];
    }
}
