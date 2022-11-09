<?php

declare(strict_types=1);

namespace Codea\Cqrs\Test;

use Codea\Cqrs\Bus\Messenger\Stamp\PayloadStamp;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class PayloadStampTest extends TestCase
{
    private const ID = 'efa08f7d-c47e-4ced-a094-754fa91d27b0';

    public function testItReturnsValidId(): void
    {
        $payload = new PayloadStamp(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
        );

        $this->assertSame(self::ID, $payload->id()->toRfc4122());
    }

    public function testItReturnsValidCreationDate(): void
    {
        $dateTime = new DateTimeImmutable();
        $payload = new PayloadStamp(
            id: Uuid::fromString(self::ID),
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
            id: Uuid::fromString(self::ID),
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
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            data: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($payload->isSuccess());
        $this->assertCount(1, $payload->data());
    }
}
