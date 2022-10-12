<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use DateTimeImmutable;

interface Query extends Message
{
    public function askedOn(): DateTimeImmutable;
}
