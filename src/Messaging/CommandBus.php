<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

use Termyn\Cqrs\Command;

interface CommandBus
{
    public function dispatch(Command $command): Payload;
}
