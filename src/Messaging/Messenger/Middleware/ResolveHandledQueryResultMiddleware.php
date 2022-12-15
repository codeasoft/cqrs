<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\Cqrs\Query;
use Termyn\Timekeeper\TimeService;

final class ResolveHandledQueryResultMiddleware implements MiddlewareInterface
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
        if ($message instanceof Query) {
            $payload = $envelope->last(HandledStamp::class)?->getResult();

            $envelope = is_iterable($payload)
                ? $envelope->with(
                    ResultStamp::success(
                        id: $message->id(),
                        createdAt: $this->timeService->measure(),
                        payload: $payload,
                    )
                ) : $envelope;
        }

        return $envelope;
    }
}
