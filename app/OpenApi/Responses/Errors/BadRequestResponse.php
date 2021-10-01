<?php

declare(strict_types=1);

namespace App\OpenApi\Responses\Errors;

use App\OpenApi\Schemas\DefaultErrorResponseSchema;
use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class BadRequestResponse extends ResponseFactory
{
    public function build(): Response
    {
        return Response::badRequest()->content(
            MediaType::json()->schema(
                DefaultErrorResponseSchema::ref()
            )
        );
    }
}
