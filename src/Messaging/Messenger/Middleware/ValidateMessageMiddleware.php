<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Termyn\Cqrs\Message;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\Cqrs\Validation\MessageValidator;
use Termyn\DateTime\Clock;

final class ValidateMessageMiddleware implements Middleware
{
    use StackTrait;

    public function __construct(
        private readonly MessageValidator $messageValidator,
        private readonly Clock $clock,
    ) {
    }

    public function handle(
        Envelope $envelope,
        Stack $stack
    ): Envelope {
        $message = $envelope->getMessage();
        if (! $message instanceof Message) {
            return $this->next($envelope, $stack);
        }

        $messageValidity = $this->messageValidator->validate($message);

        return $messageValidity->isValid()
            ? $this->next($envelope, $stack)
            : $envelope->with(
                ResultStamp::failure(
                    id: $message->id(),
                    createdAt: $this->clock->measure(),
                    errors: $messageValidity->errors,
                )
            );
    }
}
