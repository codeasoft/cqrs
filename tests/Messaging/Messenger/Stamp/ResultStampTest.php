<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use DateTimeImmutable as DateTime;
use PHPUnit\Framework\TestCase;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\Identifier\Uuid\Symfony\SymfonyUuid;

final class ResultStampTest extends TestCase
{
    private const ID = 'efa08f7d-c47e-4ced-a094-754fa91d27b0';

    public function testItReturnsValidId(): void
    {
        $resultStamp = new ResultStamp(
            id: $this->getId(),
            createdAt: new DateTime(),
        );

        $id = $resultStamp->id();

        $this->assertSame(self::ID, (string) $id);
    }

    public function testItReturnsValidCreationDate(): void
    {
        $dateTime = new DateTime();
        $resultStamp = new ResultStamp(
            id: $this->getId(),
            createdAt: $dateTime,
        );

        $this->assertSame(
            $dateTime->format(DATE_ATOM),
            $resultStamp->createdAt()->format(DATE_ATOM)
        );
    }

    public function testItHasErrorsIfItFailed(): void
    {
        $resultStamp = new ResultStamp(
            id: $this->getId(),
            createdAt: new DateTime(),
            errors: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($resultStamp->isFailure());
        $this->assertCount(1, $resultStamp->errors());
    }

    public function testItHasPayloadIfItSucceeded(): void
    {
        $resultStamp = new ResultStamp(
            id: $this->getId(),
            createdAt: new DateTime(),
            payload: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($resultStamp->isSuccess());
        $this->assertCount(1, $resultStamp->payload());
    }

    private function getId(): SymfonyUuid
    {
        return SymfonyUuid::fromString(self::ID);
    }
}
