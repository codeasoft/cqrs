<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use Codea\Cqrs\Query;
use Codea\Cqrs\Result;

interface QueryBus
{
    public function dispatch(Query $query): Result;
}
