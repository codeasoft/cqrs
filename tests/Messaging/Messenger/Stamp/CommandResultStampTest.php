<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use Termyn\Cqrs\Messaging\Messenger\Stamp\CommandResultStamp;

final class CommandResultStampTest extends ResultStampTest
{
    public function testHandled(): void
    {
        $id = $this->getId();
        $instant = $this->getInstant();

        $resultStamp = CommandResultStamp::handled($id, $instant);

        $this->assertSame($id, $resultStamp->id());
        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertTrue($resultStamp->isSuccess());
        $this->assertFalse($resultStamp->isFailure());
        $this->assertFalse($resultStamp->isRejected());

        $this->assertTrue($resultStamp->isSync());
        $this->assertFalse($resultStamp->isAsync());

        $this->assertFalse($resultStamp->hasErrors());
        $this->assertCount(0, $resultStamp->errors());
    }

    public function testSent(): void
    {
        $id = $this->getId();
        $instant = $this->getInstant();

        $resultStamp = CommandResultStamp::sent($id, $instant);

        $this->assertSame($id, $resultStamp->id());
        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertTrue($resultStamp->isSuccess());
        $this->assertFalse($resultStamp->isFailure());
        $this->assertFalse($resultStamp->isRejected());

        $this->assertTrue($resultStamp->isAsync());
        $this->assertFalse($resultStamp->isSync());

        $this->assertFalse($resultStamp->hasErrors());
        $this->assertCount(0, $resultStamp->errors());
    }

    public function testInvalid(): void
    {
        $id = $this->getId();
        $errors = $this->getErrors();
        $instant = $this->getInstant();

        $resultStamp = CommandResultStamp::invalid($id, $errors, $instant);

        $this->assertSame($id, $resultStamp->id());
        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertFalse($resultStamp->isSuccess());
        $this->assertFalse($resultStamp->isFailure());
        $this->assertTrue($resultStamp->isRejected());

        $this->assertFalse($resultStamp->isAsync());
        $this->assertFalse($resultStamp->isSync());

        $this->assertTrue($resultStamp->hasErrors());
        $this->assertCount(2, $resultStamp->errors());
    }

    public function testFailed(): void
    {
        $id = $this->getId();
        $errors = $this->getErrors();
        $instant = $this->getInstant();

        $resultStamp = CommandResultStamp::failed($id, $errors, $instant);

        $this->assertSame($id, $resultStamp->id());
        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertFalse($resultStamp->isSuccess());
        $this->assertTrue($resultStamp->isFailure());
        $this->assertFalse($resultStamp->isRejected());

        $this->assertFalse($resultStamp->isAsync());
        $this->assertFalse($resultStamp->isSync());

        $this->assertTrue($resultStamp->hasErrors());
        $this->assertCount(2, $resultStamp->errors());
    }
}
