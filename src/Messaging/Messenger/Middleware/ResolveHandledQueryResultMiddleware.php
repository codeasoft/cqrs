<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging\Messenger\Middleware;

use Codea\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Codea\Cqrs\Query;
use Codea\Timekeeper\TimeService;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;

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
            $result = $envelope->last(HandledStamp::class)?->getResult();

            $envelope = is_iterable($result)
                ? $envelope->with(
                    PayloadStamp::success(
                        id: $message->id(),
                        createdAt: $this->timeService->measure(),
                        data: $result,
                    )
                ) : $envelope;
        }

        return $envelope;
    }
}
