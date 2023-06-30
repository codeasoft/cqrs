<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Test\Messaging\Messenger\Stamp;

use Termyn\Cqrs\Messaging\Messenger\Stamp\QueryResultStamp;

final class QueryResultStampTest extends ResultStampTestCase
{
    public function testHandled(): void
    {
        $payload = $this->getPayload();
        $instant = $this->getInstant();

        $resultStamp = QueryResultStamp::handled($payload, $instant);

        $this->assertTrue($resultStamp->hasPayload());
        $this->assertCount(2, $resultStamp->payloadAsArray());

        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertTrue($resultStamp->isSuccess());
        $this->assertFalse($resultStamp->isFailure());
        $this->assertFalse($resultStamp->isRejected());

        $this->assertFalse($resultStamp->hasErrors());
        $this->assertCount(0, $resultStamp->errors());
    }

    public function testInvalid(): void
    {
        $errors = $this->getErrors();
        $instant = $this->getInstant();

        $resultStamp = QueryResultStamp::invalid($errors, $instant);

        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertFalse($resultStamp->isSuccess());
        $this->assertFalse($resultStamp->isFailure());
        $this->assertTrue($resultStamp->isRejected());

        $this->assertTrue($resultStamp->hasErrors());
        $this->assertCount(2, $resultStamp->errors());
    }

    public function testFailed(): void
    {
        $errors = $this->getErrors();
        $instant = $this->getInstant();

        $resultStamp = QueryResultStamp::failed($errors, $instant);

        $this->assertSame($instant, $resultStamp->createdAt());

        $this->assertFalse($resultStamp->isSuccess());
        $this->assertTrue($resultStamp->isFailure());
        $this->assertFalse($resultStamp->isRejected());

        $this->assertTrue($resultStamp->hasErrors());
        $this->assertCount(2, $resultStamp->errors());
    }
}
