<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use Codea\Cqrs\Bus\Payload;

interface CommandBus
{
    public function dispatch(Command $command): Payload;
}
