<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use DateTimeImmutable;

interface Command extends Message
{
    public function issuedOn(): DateTimeImmutable;
}
