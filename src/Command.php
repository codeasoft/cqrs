<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use DateTimeImmutable as DateTime;

interface Command extends Message
{
    public function issuedOn(): DateTime;
}
