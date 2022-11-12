<?php

declare(strict_types=1);

namespace Codea\Cqrs\Messaging\Messenger;

use Codea\Cqrs\Command;
use Codea\Cqrs\Messaging\CommandBus;
use Codea\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Codea\Cqrs\Messaging\Payload;
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
