<?php
namespace Apie\Common\Events;

use Apie\Core\Context\ApieContext;
use Apie\Core\Datalayers\Lists\PaginatedResult;

class ApieResourceReadList
{
    public function __construct(
        public readonly PaginatedResult $resource,
        public readonly ApieContext $context
    ) {
    }
}
