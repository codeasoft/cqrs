<?php

declare(strict_types=1);

namespace Codea\Cqrs\Validation\Validator;

use Codea\Cqrs\Message;
use Codea\Cqrs\Validation\MessageValidator;
use Codea\Cqrs\Validation\MessageValidity;
use Symfony\Component\Validator\ConstraintViolationInterface as ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidator;

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
