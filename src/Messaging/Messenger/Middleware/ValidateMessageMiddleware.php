<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Termyn\Cqrs\Message;
use Termyn\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Termyn\Cqrs\Validation\MessageValidator;
use Termyn\Timekeeper\TimeService;

final class ValidateMessageMiddleware implements Middleware
{
    use StackTrait;

    public function __construct(
        private readonly MessageValidator $messageValidator,
        private readonly TimeService $timeService,
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
                PayloadStamp::failure(
                    id: $message->id(),
                    createdAt: $this->timeService->measure(),
                    errors: $messageValidity->errors,
                )
            );
    }
}
