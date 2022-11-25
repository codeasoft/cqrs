<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Termyn\Cqrs\Messaging\Messenger\Stamp\PayloadStamp;
use Termyn\Identifier\Uuid\Symfony\SymfonyUuid;

final class PayloadStampTest extends TestCase
{
    private const ID = 'efa08f7d-c47e-4ced-a094-754fa91d27b0';

    public function testItReturnsValidId(): void
    {
        $payload = new PayloadStamp(
            id: SymfonyUuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
        );

        $id = $payload->id();
        $this->assertSame(self::ID, (string) $id);
    }

    public function testItReturnsValidCreationDate(): void
    {
        $dateTime = new DateTimeImmutable();
        $payload = new PayloadStamp(
            id: SymfonyUuid::fromString(self::ID),
            createdAt: $dateTime,
        );

        $this->assertSame(
            $dateTime->format(DATE_ATOM),
            $payload->createdAt()->format(DATE_ATOM)
        );
    }

    public function testItHasErrorsIfItFailed(): void
    {
        $payload = new PayloadStamp(
            id: SymfonyUuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            errors: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($payload->isFailure());
        $this->assertCount(1, $payload->errors());
    }

    public function testItHasPayloadIfItSucceeded(): void
    {
        $payload = new PayloadStamp(
            id: SymfonyUuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            data: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($payload->isSuccess());
        $this->assertCount(1, $payload->data());
    }
}
