<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

use DateTimeImmutable as DateTime;
use Termyn\Identifier\Id;

interface Payload
{
    public function isSuccess(): bool;

    public function isFailure(): bool;

    public function id(): Id;

    public function errors(): iterable;

    public function data(): iterable;

    public function createdAt(): DateTime;
}
