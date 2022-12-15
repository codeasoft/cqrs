<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use DateTimeImmutable as DateTime;
use Symfony\Component\Messenger\Stamp\StampInterface as Stamp;
use Termyn\Cqrs\Messaging\Payload;
use Termyn\Identifier\Id;

final readonly class PayloadStamp implements Payload, Stamp
{
    public function __construct(
        private Id $id,
        private DateTime $createdAt,
        private iterable $data = [],
        private iterable $errors = [],
    ) {
    }

    public static function success(
        Id $id,
        DateTime $createdAt,
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
        DateTime $createdAt,
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

    public function createdAt(): DateTime
    {
        return $this->createdAt;
    }
}
