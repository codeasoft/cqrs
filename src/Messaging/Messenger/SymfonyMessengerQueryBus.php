<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\Cqrs\Messaging\QueryBus;
use Termyn\Cqrs\Messaging\Result;
use Termyn\Cqrs\Query;

final class SymfonyMessengerQueryBus implements QueryBus
{
    public function __construct(
        private readonly MessageBus $messageBus,
    ) {
    }

    public function dispatch(
        Query $query,
    ): Result {
        $envelope = $this->messageBus->dispatch($query);
        $payload = $envelope->last(ResultStamp::class);

        return ($payload instanceof Result)
            ? $payload
            : throw new RuntimeException(
                sprintf(
                    'The query "%s" processing did not return any results. Check if there is a suitable query handler with a return value of type "iterable"',
                    $envelope->getMessage()::class,
                )
            );
    }
}
