<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use ArrayIterator;
use PHPUnit\Framework\TestCase;
use Termyn\DateTime\Instant;
use Termyn\Uuid\Symfony\SymfonyUuid;

abstract class ResultStampTestCase extends TestCase
{
    abstract public function testHandled(): void;

    abstract public function testInvalid(): void;

    abstract public function testFailed(): void;

    protected function getId(): SymfonyUuid
    {
        return SymfonyUuid::fromString('efa08f7d-c47e-4ced-a094-754fa91d27b0');
    }

    protected function getErrors(): array
    {
        return [
            'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'Quisque ante mi, luctus eu enim ac, consectetur rutrum nibh.',
        ];
    }

    protected function getInstant(): Instant
    {
        return Instant::of(1671478648);
    }

    protected function getPayload(): ArrayIterator
    {
        return new ArrayIterator([
            'id' => 1,
            'Name' => 'John Doe',
        ]);
    }
}
