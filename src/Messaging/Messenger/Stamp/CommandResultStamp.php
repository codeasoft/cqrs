<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use Termyn\Cqrs\Messaging\CommandResult;
use Termyn\Cqrs\Messaging\MessageStatus;
use Termyn\DateTime\Instant;
use Termyn\Id;

final readonly class CommandResultStamp extends ResultStamp implements CommandResult
{
    private function __construct(
        private Id $id,
        MessageStatus $messageStatus,
        Instant $createdAt,
        array $errors = [],
    ) {
        parent::__construct($messageStatus, $createdAt, $errors);
    }

    public static function handled(
        Id $id,
        Instant $createdAt,
    ): self {
        return new self(
            id: $id,
            messageStatus: MessageStatus::HANDLED,
            createdAt: $createdAt,
        );
    }

    public static function sent(
        Id $id,
        Instant $createdAt,
    ): self {
        return new self(
            id: $id,
            messageStatus: MessageStatus::SENT,
            createdAt: $createdAt,
        );
    }

    public static function invalid(
        Id $id,
        array $errors,
        Instant $createdAt,
    ): self {
        return new self(
            id: $id,
            messageStatus: MessageStatus::INVALID,
            createdAt: $createdAt,
            errors: $errors,
        );
    }

    public static function failed(
        Id $id,
        array $errors,
        Instant $createdAt,
    ): self {
        return new self(
            id: $id,
            messageStatus: MessageStatus::FAILED,
            createdAt: $createdAt,
            errors: $errors,
        );
    }

    public function isSync(): bool
    {
        return $this->isSuccess()
            && $this->isHandled();
    }

    public function isAsync(): bool
    {
        return $this->isSuccess()
            && $this->isSent();
    }

    public function id(): Id
    {
        return $this->id;
    }
}
