<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas;

use App\OpenApi\Props;
use App\OpenApi\Schemas\AbstractSchema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;

class SampleAssertResponseSchema extends AbstractSchema implements Reusable
{
    protected function getObjectId(): string
    {
        return 'SampleAssertResponse';
    }

    protected function getProperties(): array
    {
        return [
            Props::name(),
            Props::age(),
            Props::birthday(),
        ];
    }
}
