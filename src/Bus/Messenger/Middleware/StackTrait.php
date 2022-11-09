<?php

declare(strict_types=1);

namespace Codea\Cqrs\Bus\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface as Stack;

trait StackTrait
{
    public function next(
        Envelope $envelope,
        Stack $stack,
    ): Envelope {
        return $stack->next()->handle($envelope, $stack);
    }
}
