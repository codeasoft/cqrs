<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging;

use Codea\Cqrs\Query;

interface QueryBus
{
    public function dispatch(Query $query): Payload;
}
