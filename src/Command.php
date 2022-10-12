<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use DateTimeImmutable;

interface Command extends Message
{
    public function issuedOn(): DateTimeImmutable;
}
