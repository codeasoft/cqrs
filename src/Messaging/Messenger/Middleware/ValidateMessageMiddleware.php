<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging\Messenger\Middleware;

use Codea\Cqrs\Message;
use Codea\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Codea\Cqrs\Validation\MessageValidator;
use Codea\Timekeeper\TimeService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;

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
