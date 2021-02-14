<?php

use Apie\StatusCheckPlugin\ApiResources\Status;

$id = 'test connection check';
$status = 'Error connecting: Mock queue is empty';

return [
    0 => [
        0 => new Status($id, $status),
        1 => new Status($id, $status),
    ],
    1 => [
        0 => new Status($id, $status),
        1 => new Status($id, $status),
    ],
];
