<?php

declare(strict_types=1);

namespace Codea\Cqrs\Test;

use Codea\Messaging\Cqrs\Result;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ResultTest extends TestCase
{
    private const ID = 'efa08f7d-c47e-4ced-a094-754fa91d27b0';

    public function testItReturnsValidId(): void
    {
        $result = new Result(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
        );

        $this->assertSame(self::ID, $result->id->toRfc4122());
    }

    public function testItReturnsValidCreationDate(): void
    {
        $dateTime = new DateTimeImmutable();
        $result = new Result(
            id: Uuid::fromString(self::ID),
            createdAt: $dateTime,
        );

        $this->assertSame(
            $dateTime->format(DATE_ATOM),
            $result->createdAt->format(DATE_ATOM)
        );
    }

    public function testItHasErrorIfItFailed(): void
    {
        $result = new Result(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            errors: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($result->isFailed());
        $this->assertCount(1, $result->errors);
    }

    public function testItHasPayloadIfItSucceeded(): void
    {
        $result = new Result(
            id: Uuid::fromString(self::ID),
            createdAt: new DateTimeImmutable(),
            payload: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($result->isSucceeded());
        $this->assertCount(1, $result->payload);
    }
}