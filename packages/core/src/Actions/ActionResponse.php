<?php
namespace Apie\Core\Actions;

use Apie\Common\ApieFacade;
use Apie\Core\Context\ApieContext;

final class ActionResponse
{
    public readonly mixed $result;

    public readonly mixed $resource;

    private mixed $nativeData;

    private function __construct(private readonly ApieFacade $apieFacade, public readonly ApieContext $apieContext, public readonly ActionResponseStatus $status)
    {
    }

    public static function createCreationSuccess(ApieFacade $apieFacade, ApieContext $apieContext, mixed $result, mixed $resource): self
    {
        $res = new self($apieFacade, $apieContext, ActionResponseStatus::CREATED);
        $res->result = $result;
        $res->resource = $resource;
        return $res;
    }

    public static function createRunSuccess(ApieFacade $apieFacade, ApieContext $apieContext, mixed $result, mixed $resource): self
    {
        $res = new self($apieFacade, $apieContext, ActionResponseStatus::SUCCESS);
        $res->result = $result;
        $res->resource = $resource;
        return $res;
    }

    public function getResultAsNativeData(): mixed
    {
        if (!isset($this->nativeData)) {
            $this->nativeData = $this->apieFacade->normalize($this->result, $this->apieContext);
        }
        return $this->nativeData;
    }
}
