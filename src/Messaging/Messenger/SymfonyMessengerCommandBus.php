<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger;

use RuntimeException;
use Symfony\Component\Messenger\MessageBusInterface as MessageBus;
use Termyn\Cqrs\Command;
use Termyn\Cqrs\Messaging\CommandBus;
use Termyn\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Termyn\Cqrs\Messaging\Payload;

final class SymfonyMessengerCommandBus implements CommandBus
{
    public function __construct(
        private readonly MessageBus $messageBus,
    ) {

    }

    public function dispatch(
        Command $command,
    ): Payload {
        $envelope = $this->messageBus->dispatch($command);
        $payload = $envelope->last(PayloadStamp::class);

        return ($payload instanceof Payload)
            ? $payload
            : throw new RuntimeException(
                sprintf(
                    'The command "%s" processing did not return any results. Check if there is a suitable command handler without an return value',
                    $envelope->getMessage()::class,
                )
            );
    }
}
