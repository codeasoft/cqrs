<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use DateTimeImmutable;
use Symfony\Component\Messenger\Stamp\StampInterface as Stamp;
use Symfony\Component\Uid\Uuid;
use Termyn\Cqrs\Messaging\Payload;

final class PayloadStamp implements Payload, Stamp
{
    public function __construct(
        private readonly Uuid $id,
        private readonly DateTimeImmutable $createdAt,
        private readonly iterable $data = [],
        private readonly iterable $errors = [],
    ) {
    }

    public static function success(
        Uuid $id,
        DateTimeImmutable $createdAt,
        iterable $data = [],
    ): self {
        return new self(
            id: $id,
            createdAt: $createdAt,
            data: $data,
        );
    }

    public static function failure(
        Uuid $id,
        DateTimeImmutable $createdAt,
        iterable $errors,
    ): self {
        return new self(
            id: $id,
            createdAt: $createdAt,
            errors: $errors,
        );
    }

    public function isSuccess(): bool
    {
        return empty($this->errors);
    }

    public function isFailure(): bool
    {
        return ! $this->isSuccess();
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function errors(): iterable
    {
        return $this->errors;
    }

    public function data(): iterable
    {
        return $this->data;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
