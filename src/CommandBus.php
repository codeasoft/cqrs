<?php

declare(strict_types=1);

namespace Codea\Cqrs;

interface CommandBus
{
    public function dispatch(Command $command): Result;
}
