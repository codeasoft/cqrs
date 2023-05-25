<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Termyn\Cqrs\Command;
use Termyn\Cqrs\Messaging\CommandBus;
use Termyn\Cqrs\Messaging\CommandResult;
use Termyn\Cqrs\Messaging\Messenger\Stamp\CommandResultStamp;

final readonly class SymfonyMessengerCommandBus implements CommandBus
{
    public function __construct(
        private MessageBus $messageBus,
    ) {
    }

    public function dispatch(Command $command): CommandResult
    {
        $envelope = $this->messageBus->dispatch($command);

        $result = $envelope->last(CommandResultStamp::class);

        return $result instanceof CommandResult
            ? $result
            : throw new RuntimeException(
                sprintf(
                    'The command "%s" processing did not return any results. Check if there is a suitable command handler without an return value',
                    $envelope->getMessage()::class,
                )
            );
    }
}
