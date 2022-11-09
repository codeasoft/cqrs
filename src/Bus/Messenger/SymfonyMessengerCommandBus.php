<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus\Messenger;

use Codea\Cqrs\Bus\Messenger\Stamp\ResultStamp;
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
        $result = $envelope->last(ResultStamp::class);

        return ($result instanceof Result)
            ? $result
            : throw new RuntimeException(
                sprintf(
                    'The command "%s" processing did not return any results. Check if there is a suitable command handler without an return value',
                    $envelope->getMessage()::class,
                )
            );
    }
}
