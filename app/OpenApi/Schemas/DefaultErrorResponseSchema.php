<?php

declare(strict_types=1);

namespace App\OpenApi\Schemas;

use GoldSpecDigital\ObjectOrientedOAS\Contracts\SchemaContract;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AnyOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Not;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\SchemaFactory;

class DefaultErrorResponseSchema extends SchemaFactory implements Reusable
{
    /**
     * @return AllOf|AnyOf|Not|OneOf|Schema
     */
    public function build(): SchemaContract
    {
        return Schema::object('DefaultErrorResponse')
            ->properties(
                Schema::string('code'),
                Schema::string('message'),
                Schema::array('errors')->items(
                    Schema::object()->properties(
                        Schema::string('field'),
                        Schema::array('messages')->items(Schema::string())
                    )->required(
                        'field',
                        'messages'
                    ),
                ),
            )->required(
                'code',
                'message'
            );
    }
}
