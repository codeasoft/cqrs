<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\Id;

interface Message
{
    public function id(): Id;
}
