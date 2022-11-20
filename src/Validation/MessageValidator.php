<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Validation;

use Termyn\Cqrs\Message;

interface MessageValidator
{
    public function validate(Message $message): MessageValidity;
}
