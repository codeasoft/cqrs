<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\Cqrs\Query;
use Termyn\DateTime\Clock;

final class ResolveHandledQueryResultMiddleware implements MiddlewareInterface
{
    use StackTrait;

    public function __construct(
        private readonly Clock $clock,
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

            return $envelope->with(
                ResultStamp::success(
                    id: $message->id(),
                    createdAt: $this->clock->measure(),
                    payload: is_iterable($payload) ? $payload : [$payload],
                )
            );
        }

        return $envelope;
    }
}
