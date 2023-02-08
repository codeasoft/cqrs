<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface as Stamp;
use Termyn\Cqrs\Messaging\Result;
use Termyn\DateTime\Instant;
use Termyn\Id;

final readonly class ResultStamp implements Result, Stamp
{
    public function __construct(
        private Id $id,
        private Instant $createdAt,
        private iterable $payload = [],
        private iterable $errors = [],
    ) {
    }

    public static function success(
        Id $id,
        Instant $createdAt,
        iterable $payload = [],
    ): self {
        return new self(
            id: $id,
            createdAt: $createdAt,
            payload: $payload,
        );
    }

    public static function failure(
        Id $id,
        Instant $createdAt,
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

    public function hasPayload(): bool
    {
        return count($this->payload) > 0;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function errors(): iterable
    {
        return $this->errors;
    }

    public function payload(): iterable
    {
        return $this->payload;
    }

    public function createdAt(): Instant
    {
        return $this->createdAt;
    }
}
