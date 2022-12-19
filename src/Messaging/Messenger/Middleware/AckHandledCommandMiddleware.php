<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Termyn\Clock;
use Termyn\Cqrs\Command;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;

final class AckHandledCommandMiddleware implements Middleware
{
    use StackTrait;

    public function __construct(
        private readonly Clock $clock,
    ) {
    }

    public function handle(
        Envelope $envelope,
        Stack $stack,
    ): Envelope {
        $envelope = $this->next($envelope, $stack);

        $message = $envelope->getMessage();
        if ($message instanceof Command) {
            $envelope = $envelope->last(HandledStamp::class)
                ? $envelope->with(
                    ResultStamp::success(
                        id: $message->id(),
                        createdAt: $this->clock->measure()
                    )
                ) : $envelope;
        }

        return $envelope;
    }
}
