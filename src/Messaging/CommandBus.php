<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging;

use Codea\Cqrs\Command;

interface CommandBus
{
    public function dispatch(Command $command): Payload;
}
