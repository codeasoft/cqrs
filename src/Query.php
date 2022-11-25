<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use DateTimeImmutable as DateTime;

interface Query extends Message
{
    public function askedOn(): DateTime;
}
