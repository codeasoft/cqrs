<?php

declare(strict_types=1);

namespace Codea\Cqrs;

interface QueryBus
{
    public function dispatch(Query $query): Result;
}
