<?php

namespace Apie\StatusCheckPlugin\StatusChecks;

use Apie\StatusCheckPlugin\ApiResources\Status;

/**
 * Interface for a single status check.
 */
interface StatusCheckInterface
{
    /**
     * Gets current status of the status check.
     *
     * @return Status
     */
    public function getStatus(): Status;
}
