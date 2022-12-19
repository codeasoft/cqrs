<?php

declare(strict_types=1);

namespace Termyn\Cqrs;

use Termyn\GlobalId;

interface Message
{
    public function id(): GlobalId;
}
