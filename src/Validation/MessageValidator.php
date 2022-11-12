<?php

declare(strict_types=1);

namespace Codea\Cqrs\Validation;

use Codea\Cqrs\Message;

interface MessageValidator
{
    public function validate(Message $message): MessageValidity;
}
