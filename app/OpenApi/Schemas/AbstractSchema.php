<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas;

use App\OpenApi\PropModel;
use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

abstract class AbstractSchema extends SchemaFactory
{
    /**
     * @return AllOf|AnyOf|Not|OneOf|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object($this->getObjectId())
            ->properties(
                ...$this->generateSchemaProperties()
            )->required(
                ...$this->generateSchemaRequired()
            );
    }

    abstract protected function getObjectId(): string;

    /**
     * @return PropModel[]
     */
    abstract protected function getProperties(): array;

    private function generateSchemaProperties(): array
    {
        $props = $this->getProperties();
        usort($props, function (PropModel $prop1, PropModel $prop2) {
            return ($prop1->getObjectId() <= $prop2->getObjectId()) ? -1 : 1;
        });
        return array_map(
            fn (PropModel $prop) => $prop->getSchema(),
            $props
        );
    }

    private function generateSchemaRequired(): array
    {
        $required = array_filter(
            $this->getProperties(),
            fn (PropModel $prop) => !$prop->isNullable(),
        );
        usort($required, function (PropModel $prop1, PropModel $prop2) {
            return ($prop1->getObjectId() <= $prop2->getObjectId()) ? -1 : 1;
        });
        return array_map(
            fn (PropModel $prop) => $prop->getObjectId(),
            $required
        );
    }
}
