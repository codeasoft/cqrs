<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\Instant;

interface Query extends Message
{
    public function askedOn(): Instant;
}
