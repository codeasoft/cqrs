<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus;

use Codea\Cqrs\Messaging\QueryBus;
use Codea\Cqrs\Query;
use Codea\Cqrs\Result;
use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;

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

        return $envelope->last(Result::class)
            ?? throw new RuntimeException(
                sprintf(
                    'The query "%s" processing did not return any results. Check if there is a suitable query handler with a return value of type "iterable"',
                    $envelope->getMessage()::class,
                )
            );
    }
}
