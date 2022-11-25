<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use DateTimeImmutable as DateTime;
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
            createdAt: new DateTime(),
        );

        $id = $payload->id();

        $this->assertSame(self::ID, (string) $id);
    }

    public function testItReturnsValidCreationDate(): void
    {
        $dateTime = new DateTime();
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
            createdAt: new DateTime(),
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
            createdAt: new DateTime(),
            data: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($payload->isSuccess());
        $this->assertCount(1, $payload->data());
    }
}
