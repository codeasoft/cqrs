<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

use Termyn\DateTime\Instant;

interface Result
{
    public function isSuccess(): bool;

    public function isFailure(): bool;

    public function isInvalid(): bool;

    public function hasErrors(): bool;

    public function errors(): iterable;

    public function createdAt(): Instant;
}
