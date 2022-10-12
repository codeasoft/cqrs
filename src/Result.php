<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use Codea\Cqrs\Bus\Stamp\ResultStamp;
use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class Result implements ResultStamp
{
    public function __construct(
        public readonly Uuid $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly iterable $payload = [],
        public readonly iterable $errors = [],
    ) {
    }

    public static function success(
        Uuid $id,
        DateTimeImmutable $createdAt,
        iterable $payload = [],
    ): self {
        return new self(
            id: $id,
            createdAt: $createdAt,
            payload: $payload,
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
}
