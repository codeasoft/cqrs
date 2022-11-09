<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus\Messenger\Middleware;

use Codea\Cqrs\Bus\Messenger\Stamp\ResultStamp;
use Codea\Cqrs\Message;
use Codea\Cqrs\Message\MessageValidator;
use Codea\Cqrs\Result;
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
                ResultStamp::failure(
                    id: $message->id(),
                    createdAt: $this->timeService->measure(),
                    errors: $messageValidity->errors,
                )
            );
    }
}
