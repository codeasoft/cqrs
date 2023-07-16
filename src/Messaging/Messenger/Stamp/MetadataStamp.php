<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface as Stamp;
use Termyn\DateTime\Instant;
use Termyn\Id;

final readonly class MetadataStamp implements Stamp
{
    public function __construct(
        public Id $messageId,
        public Instant $publishedAt,
    ) {

    }
}
