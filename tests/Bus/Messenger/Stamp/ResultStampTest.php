<?php

declare(strict_types=1);

namespace Codea\Cqrs\Test;

use Codea\Cqrs\Bus\Messenger\Stamp\ResultStamp;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ResultStampTest extends TestCase
{
    private const ID = 'efa08f7d-c47e-4ced-a094-754fa91d27b0';

    public function testItReturnsValidId(): void
    {
        $result = new ResultStamp(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
        );

        $this->assertSame(self::ID, $result->id->toRfc4122());
    }

    public function testItReturnsValidCreationDate(): void
    {
        $dateTime = new DateTimeImmutable();
        $result = new ResultStamp(
            id: Uuid::fromString(self::ID),
            createdAt: $dateTime,
        );

        $this->assertSame(
            $dateTime->format(DATE_ATOM),
            $result->createdAt->format(DATE_ATOM)
        );
    }

    public function testItHasErrorsIfItFailed(): void
    {
        $result = new ResultStamp(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            errors: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($result->isFailure());
        $this->assertCount(1, $result->errors);
    }

    public function testItHasPayloadIfItSucceeded(): void
    {
        $result = new ResultStamp(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            payload: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($result->isSuccess());
        $this->assertCount(1, $result->payload);
    }
}
