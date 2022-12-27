<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\DateTime\Instant;

interface Query extends Message
{
    public function askedOn(): Instant;
}
