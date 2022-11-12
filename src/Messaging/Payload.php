<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

interface Payload
{
    public function isSuccess(): bool;

    public function isFailure(): bool;

    public function id(): Uuid;

    public function errors(): iterable;

    public function data(): iterable;

    public function createdAt(): DateTimeImmutable;
}
