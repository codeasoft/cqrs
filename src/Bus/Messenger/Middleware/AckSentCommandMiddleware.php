<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus\Messenger\Middleware;

use Codea\Cqrs\Bus\Messenger\Stamp\PayloadStamp;
use Codea\Cqrs\Command;
use Codea\Timekeeper\TimeService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface as Middleware;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\SentStamp;

final class AckSentCommandMiddleware implements Middleware
{
    use StackTrait;

    public function __construct(
        private readonly TimeService $timeService,
    ) {
    }

    public function handle(
        Envelope $envelope,
        Stack $stack
    ): Envelope {
        $envelope = $this->next($envelope, $stack);

        $message = $envelope->getMessage();
        if ($message instanceof Command) {
            $envelope = $envelope->last(SentStamp::class)
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
