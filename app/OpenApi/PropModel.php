<?php

declare(strict_types=1);

namespace App\OpenApi;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;

class PropModel
{
    public function __construct(
        private Schema $schema,
        private string $objectId,
        private bool $nullable
    ) {
    }

    public function getSchema(): Schema
    {
        return $this->nullable ? $this->schema->nullable() : $this->schema;
    }

    public function getObjectId(): string
    {
        return $this->objectId;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }
}
