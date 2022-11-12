<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging\Messenger\Middleware;

use Codea\Cqrs\Command;
use Codea\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Codea\Timekeeper\TimeService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class AckHandledCommandMiddleware implements Middleware
{
    use StackTrait;

    public function __construct(
        private readonly TimeService $timeService,
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
                    PayloadStamp::success(
                        id: $message->id(),
                        createdAt: $this->timeService->measure()
                    )
                ) : $envelope;
        }

        return $envelope;
    }
}
