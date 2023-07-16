<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Termyn\Cqrs\Messaging\Messenger\Stamp\QueryResultStamp;
use Termyn\Cqrs\Query;
use Termyn\DateTime\Clock;

final readonly class ResolveHandledQueryResultMiddleware implements MiddlewareInterface
{
    use StackTrait;

    public function __construct(
        private Clock $clock,
    ) {
    }

    public function handle(Envelope $envelope, Stack $stack): Envelope
    {
        $envelope = $this->next($envelope, $stack);

        $message = $envelope->getMessage();
        if ($message instanceof Query) {
            $payload = $envelope->last(HandledStamp::class)?->getResult();

            $envelope = $envelope->with(
                QueryResultStamp::handled(
                    payload: is_iterable($payload) ? $payload : [$payload],
                    createdAt: $this->clock->measure(),
                )
            );
        }

        return $envelope;
    }
}
