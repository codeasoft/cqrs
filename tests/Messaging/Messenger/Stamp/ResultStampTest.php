<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use PHPUnit\Framework\TestCase;
use Termyn\Cqrs\Messaging\Messenger\Stamp\ResultStamp;
use Termyn\DateTime\Instant;
use Termyn\Uuid\Symfony\SymfonyUuid;

final class ResultStampTest extends TestCase
{
    public function testItReturnsValidId(): void
    {
        $id = $this->getId();
        $instant = $this->getInstant();

        $resultStamp = new ResultStamp($id, $instant);

        $this->assertSame(
            expected: $id,
            actual: $resultStamp->id(),
        );
    }

    public function testItReturnsValidCreationDate(): void
    {
        $id = $this->getId();
        $instant = $this->getInstant();

        $resultStamp = new ResultStamp($id, $instant);

        $this->assertSame(
            expected: $instant,
            actual: $resultStamp->createdAt(),
        );
    }

    public function testItHasErrorsIfItFailed(): void
    {
        $id = $this->getId();
        $instant = $this->getInstant();

        $resultStamp = new ResultStamp(
            id: $id,
            createdAt: $instant,
            errors: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($resultStamp->isFailure());
        $this->assertCount(1, $resultStamp->errors());
    }

    public function testItHasPayloadIfItSucceeded(): void
    {
        $id = $this->getId();
        $instant = $this->getInstant();

        $resultStamp = new ResultStamp(
            id: $id,
            createdAt: $instant,
            payload: [
                'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            ],
        );

        $this->assertTrue($resultStamp->isSuccess());
        $this->assertCount(1, $resultStamp->payload());
    }

    private function getId(): SymfonyUuid
    {
        return SymfonyUuid::fromString('efa08f7d-c47e-4ced-a094-754fa91d27b0');
    }

    private function getInstant(): Instant
    {
        return Instant::of(1671478648);
    }
}
