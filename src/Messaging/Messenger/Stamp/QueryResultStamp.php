<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use Termyn\Cqrs\Messaging\MessageStatus;
use Termyn\Cqrs\Messaging\QueryResult;
use Termyn\DateTime\Instant;

final readonly class QueryResultStamp extends ResultStamp implements QueryResult
{
    private function __construct(
        MessageStatus $messageStatus,
        Instant $createdAt,
        private iterable $payload = [],
        array $errors = [],
    ) {
        parent::__construct($messageStatus, $createdAt, $errors);
    }

    public static function handled(
        iterable $payload,
        Instant $createdAt,
    ): self {
        return new self(
            messageStatus: MessageStatus::HANDLED,
            createdAt: $createdAt,
            payload: $payload,
        );
    }

    public static function invalid(
        array $errors,
        Instant $createdAt,
    ): self {
        return new self(
            messageStatus: MessageStatus::INVALID,
            createdAt: $createdAt,
            errors: $errors,
        );
    }

    public static function failed(
        array $errors,
        Instant $createdAt,
    ): self {
        return new self(
            messageStatus: MessageStatus::FAILED,
            createdAt: $createdAt,
            errors: $errors,
        );
    }

    public function hasPayload(): bool
    {
        return count($this->payloadAsArray()) > 0;
    }

    public function payload(): iterable
    {
        return $this->payload;
    }

    public function payloadAsArray(): array
    {
        return [...$this->payload];
    }
}
