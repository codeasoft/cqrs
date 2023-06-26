<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface as Stamp;
use Termyn\Cqrs\Messaging\MessageStatus;
use Termyn\Cqrs\Messaging\Result;
use Termyn\DateTime\Instant;

abstract readonly class ResultStamp implements Result, Stamp
{
    public function __construct(
        private MessageStatus $messageStatus,
        private Instant $createdAt,
        private array $errors = [],
    ) {
    }

    public function isSuccess(): bool
    {
        return $this->messageStatus->isHandled()
            || $this->messageStatus->isSent();
    }

    public function isFailure(): bool
    {
        return $this->messageStatus->isFailed();
    }

    public function isInvalid(): bool
    {
        return $this->messageStatus->isInvalid();
    }

    public function isRejected(): bool
    {
        return $this->messageStatus->isInvalid();
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function createdAt(): Instant
    {
        return $this->createdAt;
    }

    protected function isHandled(): bool
    {
        return $this->messageStatus->isHandled();
    }

    protected function isSent(): bool
    {
        return $this->messageStatus->isSent();
    }
}
