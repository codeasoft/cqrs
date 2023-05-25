<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Validation;

final readonly class MessageValidity
{
    public array $errors;

    public function __construct(
        public string $name,
        string ...$errors
    ) {
        $this->errors = $errors;
    }

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }
}
