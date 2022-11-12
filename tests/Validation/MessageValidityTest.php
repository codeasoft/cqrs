<?php

declare(strict_types=1);

namespace Codea\Cqrs\Test\Validation;

use Codea\Cqrs\Command;
use Codea\Cqrs\Validation\MessageValidity;
use PHPUnit\Framework\TestCase;

final class MessageValidityTest extends TestCase
{
    public function testItReturnsValidPublicPropertyValues(): void
    {
        $messageValidity = new MessageValidity(
            Command::class,
            ...[
                'Command property error message',
            ],
        );

        $this->assertSame(Command::class, $messageValidity->name);
        $this->assertCount(1, $messageValidity->errors);
    }

    public function testItIsMessageValid(): void
    {
        $messageValidity = new MessageValidity(
            Command::class,
            ...[],
        );

        $this->assertTrue($messageValidity->isValid());
    }

    public function testItIsMessageInvalid(): void
    {
        $messageValidity = new MessageValidity(
            Command::class,
            ...[
                'Command property error message',
            ],
        );

        $this->assertFalse($messageValidity->isValid());
    }
}
