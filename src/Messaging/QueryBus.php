<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

use Termyn\Cqrs\Query;

interface QueryBus
{
    public function dispatch(Query $query): Result;
}
