<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use DateTimeImmutable;

interface Query extends Message
{
    public function askedOn(): DateTimeImmutable;
}
