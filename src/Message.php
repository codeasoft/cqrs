<?php

declare(strict_types=1);

namespace Codea\Cqrs;

use Symfony\Component\Uid\Uuid;

interface Message
{
    public function id(): Uuid;
}
