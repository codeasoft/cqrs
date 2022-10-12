<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus\Middleware;

use Codea\Cqrs\Query;
use Codea\Cqrs\Result;
use Codea\Timekeeper\TimeService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class GetHandledQueryResultMiddleware implements MiddlewareInterface
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
                    Result::success(
                        id: $message->id(),
                        createdAt: $this->timeService->measure(),
                        payload: $payload,
                    )
                ) : $envelope;
        }

        return $envelope;
    }
}
