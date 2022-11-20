<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Symfony\Component\Uid\Uuid;

interface Message
{
    public function id(): Uuid;
}
