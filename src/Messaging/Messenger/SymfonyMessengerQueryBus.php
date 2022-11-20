<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Termyn\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Termyn\Cqrs\Messaging\Payload;
use Termyn\Cqrs\Messaging\QueryBus;
use Termyn\Cqrs\Query;

final class SymfonyMessengerQueryBus implements QueryBus
{
    public function __construct(
        private readonly MessageBus $messageBus,
    ) {
    }

    public function dispatch(
        Query $query,
    ): Payload {
        $envelope = $this->messageBus->dispatch($query);
        $payload = $envelope->last(PayloadStamp::class);

        return ($payload instanceof Payload)
            ? $payload
            : throw new RuntimeException(
                sprintf(
                    'The query "%s" processing did not return any results. Check if there is a suitable query handler with a return value of type "iterable"',
                    $envelope->getMessage()::class,
                )
            );
    }
}
