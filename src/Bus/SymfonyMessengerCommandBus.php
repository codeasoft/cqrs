<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus;

use Codea\Cqrs\Command;
use Codea\Cqrs\CommandBus;
use Codea\Cqrs\Result;
use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;

final class SymfonyMessengerCommandBus implements CommandBus
{
    public function __construct(
        private readonly MessageBus $messageBus,
    ) {

    }

    public function dispatch(
        Command $command,
    ): Result {
        $envelope = $this->messageBus->dispatch($command);

        return $envelope->last(Result::class)
            ?? throw new RuntimeException(
                sprintf(
                    'The command "%s" processing did not return any results. Check if there is a suitable command handler without an return value',
                    $envelope->getMessage()::class,
                )
            );
    }
}
