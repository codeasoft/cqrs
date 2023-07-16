<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Termyn\Cqrs\Messaging\Messenger\Stamp\MetadataStamp;
use Termyn\Cqrs\Messaging\Messenger\Stamp\QueryResultStamp;
use Termyn\Cqrs\Messaging\QueryBus;
use Termyn\Cqrs\Messaging\QueryResult;
use Termyn\Cqrs\Query;
use Termyn\DateTime\Clock;
use Termyn\Uuid\UuidGenerator;

final readonly class SymfonyMessengerQueryBus implements QueryBus
{
    public function __construct(
        private MessageBus $messageBus,
        private UuidGenerator $uuidGenerator,
        private Clock $clock,
    ) {
    }

    public function dispatch(Query $query): QueryResult
    {
        $envelope = $this->messageBus->dispatch($query, [
            new MetadataStamp(
                $this->uuidGenerator->generate(),
                $this->clock->measure(),
            ),
        ]);

        $result = $envelope->last(QueryResultStamp::class);

        return $result instanceof QueryResult
            ? $result
            : throw new RuntimeException(
                sprintf(
                    'The query "%s" processing did not return any results. Check if there is a suitable query handler with a return value of type "iterable"',
                    $envelope->getMessage()::class,
                )
            );
    }
}
