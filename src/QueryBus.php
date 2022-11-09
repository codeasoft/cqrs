<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use Codea\Cqrs\Bus\Payload;

interface QueryBus
{
    public function dispatch(Query $query): Payload;
}
