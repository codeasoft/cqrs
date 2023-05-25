<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\Id;

interface Command extends Message
{
    public function id(): Id;
}
