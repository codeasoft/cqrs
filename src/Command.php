<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\DateTime\Instant;

interface Command extends Message
{
    public function issuedAt(): Instant;
}
