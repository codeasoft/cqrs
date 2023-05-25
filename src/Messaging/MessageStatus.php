<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging;

enum MessageStatus: string
{
    case HANDLED = 'handled';

    case SENT = 'sent';

    case INVALID = 'invalid';

    case FAILED = 'failed';

    public function isHandled(): bool
    {
        return $this->value === 'handled';
    }

    public function isSent(): bool
    {
        return $this->value === 'sent';
    }

    public function isInvalid(): bool
    {
        return $this->value === 'invalid';
    }

    public function isFailed(): bool
    {
        return $this->value === 'failed';
    }
}
