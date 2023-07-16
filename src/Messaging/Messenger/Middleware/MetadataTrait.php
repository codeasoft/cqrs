<?php

declare(strict_types=1);

namespace Termyn\Cqrs\Messaging\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Termyn\Cqrs\Messaging\Messenger\Exception\NoSuchMetadataException;
use Termyn\Cqrs\Messaging\Messenger\Stamp\MetadataStamp;

trait MetadataTrait
{
    public function getMetadata(Envelope $envelope): MetadataStamp
    {
        return $envelope->last(MetadataStamp::class) ?? throw new NoSuchMetadataException();
    }
}
