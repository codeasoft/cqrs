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
    use MetadataTrait;
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

        $metadata = $this->getMetadata($envelope);
        $validity = $this->messageValidator->validate($message);

        return $validity->isValid()
            ? $this->next($envelope, $stack)
            : $envelope->with(
                CommandResultStamp::invalid(
                    id: $metadata->messageId,
                    errors: $validity->errors,
                    createdAt: $this->clock->measure(),
                )
            );
    }
}
