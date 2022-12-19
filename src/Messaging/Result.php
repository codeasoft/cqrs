<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

use Termyn\Id;
use Termyn\Instant;

interface Result
{
    public function isSuccess(): bool;

    public function isFailure(): bool;

    public function id(): Id;

    public function errors(): iterable;

    public function payload(): iterable;

    public function createdAt(): Instant;
}
