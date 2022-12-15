<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use DateTimeImmutable as DateTime;
use Termyn\Identifier\Gid;

interface Command extends Message
{
    public function id(): Gid;

    public function issuedOn(): DateTime;
}
