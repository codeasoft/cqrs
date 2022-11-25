<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use DateTimeImmutable;
use Symfony\Component\Messenger\Stamp\StampInterface as Stamp;
use Termyn\Cqrs\Messaging\Payload;
use Termyn\Identifier\Id;

final class PayloadStamp implements Payload, Stamp
{
    public function __construct(
        private readonly Id $id,
        private readonly DateTimeImmutable $createdAt,
        private readonly iterable $data = [],
        private readonly iterable $errors = [],
    ) {
    }

    public static function success(
        Id $id,
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
        Id $id,
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

    public function id(): Id
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
