<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\CatSchema;
use App\OpenApi\Schemas\DogSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\OneOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class OneOfSampleRequestBody extends RequestBodyFactory implements Reusable
{
    public function build(): RequestBody
    {
        return RequestBody::create('OneOfSampleRequestBody')->content(
            MediaType::json()->schema(
                OneOf::create()->schemas(
                    CatSchema::ref(),
                    DogSchema::ref(),
                )
            )
        );
    }
}
