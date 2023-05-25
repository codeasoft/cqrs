<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Termyn\Cqrs\Command;
use Termyn\Cqrs\Messaging\Messenger\Stamp\CommandResultStamp;
use Termyn\Cqrs\Validation\MessageValidator;
use Termyn\DateTime\Clock;

final readonly class ValidateCommandMiddleware implements Middleware
{
    use StackTrait;

    public function __construct(
        private MessageValidator $messageValidator,
        private Clock $clock,
    ) {
    }

    public function handle(
        Envelope $envelope,
        Stack $stack
    ): Envelope {
        $message = $envelope->getMessage();
        if (! $message instanceof Command) {
            return $this->next($envelope, $stack);
        }

        $messageValidity = $this->messageValidator->validate($message);

        return $messageValidity->isValid()
            ? $this->next($envelope, $stack)
            : $envelope->with(
                CommandResultStamp::invalid(
                    id: $message->id(),
                    errors: $messageValidity->errors,
                    createdAt: $this->clock->measure(),
                )
            );
    }
}
