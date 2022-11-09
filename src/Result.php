<?php

declare(strict_types=1);

namespace Codea\Cqrs;

interface Result
{
    public function isSuccess(): bool;

    public function isFailure(): bool;
}
