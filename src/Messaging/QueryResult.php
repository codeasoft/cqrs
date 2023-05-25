<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

interface QueryResult extends Result
{
    public function hasPayload(): bool;

    public function payload(): iterable;

    public function payloadAsArray(): array;
}
