<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\Instant;

interface Command extends Message
{
    public function issuedOn(): Instant;
}
