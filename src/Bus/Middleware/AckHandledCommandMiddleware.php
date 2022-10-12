<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus\Middleware;

use Codea\Cqrs\Command;
use Codea\Cqrs\Result;
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
                    Result::success($message->id(), $this->timeService->measure())
                ) : $envelope;
        }

        return $envelope;
    }
}
