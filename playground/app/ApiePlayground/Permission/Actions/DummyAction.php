<?php
namespace App\ApiePlayground\Permission\Actions;

use Apie\Core\Attributes\Context;
use Apie\Core\Datalayers\ApieDatalayer;
use App\ApiePlayground\Permission\Identifiers\UserIdentifier;
use App\ApiePlayground\Permission\Resources\User;

class DummyAction {
    public function __invoke(
        #[Context()] ApieDatalayer $apieDatalayer,
        UserIdentifier $userId
    ): User {
        return $apieDatalayer->find($userId);
    }

    public function sum(float... $numbers): float
    {
        return array_sum($numbers);
    }
}