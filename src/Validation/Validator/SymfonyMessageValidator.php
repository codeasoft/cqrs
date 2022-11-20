<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Validation\Validator;

use Symfony\Component\Validator\ConstraintViolationInterface as ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidator;
use Termyn\Cqrs\Message;
use Termyn\Cqrs\Validation\MessageValidator;
use Termyn\Cqrs\Validation\MessageValidity;

final class SymfonyMessageValidator implements MessageValidator
{
    public function __construct(
        private readonly SymfonyValidator $symfonyValidator,
    ) {
    }

    public function validate(
        Message $message,
    ): MessageValidity {
        $errors = array_map(
            fn (ConstraintViolation $constraintViolation): string => (string) $constraintViolation->getMessage(),
            iterator_to_array(
                $this->symfonyValidator->validate($message)
            ),
        );

        return new MessageValidity($message::class, ...$errors);
    }
}
