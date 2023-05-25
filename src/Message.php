<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\DateTime\Instant;

interface Message
{
    public function publishedAt(): Instant;
}
