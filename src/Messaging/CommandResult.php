<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

use Termyn\Id;

interface CommandResult extends Result
{
    public function id(): Id;

    public function isSync(): bool;

    public function isAsync(): bool;
}
