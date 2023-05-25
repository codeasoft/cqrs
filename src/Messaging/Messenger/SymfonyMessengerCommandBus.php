<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Termyn\Cqrs\Command;
use Termyn\Cqrs\Messaging\CommandBus;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\Cqrs\Messaging\Result;

final readonly class SymfonyMessengerCommandBus implements CommandBus
{
    public function __construct(
        private MessageBus $messageBus,
    ) {

    }

    public function dispatch(
        Command $command,
    ): Result {
        $envelope = $this->messageBus->dispatch($command);
        $payload = $envelope->last(ResultStamp::class);

        return ($payload instanceof Result)
            ? $payload
            : throw new RuntimeException(
                sprintf(
                    'The command "%s" processing did not return any results. Check if there is a suitable command handler without an return value',
                    $envelope->getMessage()::class,
                )
            );
    }
}
