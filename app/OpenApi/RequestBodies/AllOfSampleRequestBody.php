<?php

namespace App\OpenApi\RequestBodies;

use App\OpenApi\Schemas\CatSchema;
use App\OpenApi\Schemas\DogSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\AllOf;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\RequestBody;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\RequestBodyFactory;

class AllOfSampleRequestBody extends RequestBodyFactory implements Reusable
{
    public function build(): RequestBody
    {
        return RequestBody::create('AllOfSampleRequestBody')->content(
            MediaType::json()->schema(
                AllOf::create()->schemas(
                    CatSchema::ref(),
                    DogSchema::ref(),
                )
            )
        );
    }
}
