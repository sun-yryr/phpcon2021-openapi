<?php

declare(strict_types=1);

namespace Tests\Feature;

use cebe\openapi\ReferenceContext;
use cebe\openapi\spec\OpenApi;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class OpenApiValidator
{
    private ValidatorBuilder $validator;

    public function __construct(string $schemaPath)
    {
        $strSchema = file_get_contents($schemaPath);
        $jsonSchema = json_decode($strSchema, true);
        $schema = new OpenApi($jsonSchema);
        // ref(参照)を解決する
        $schema->resolveReferences(new ReferenceContext($schema, '/'));
        $this->validator = (new ValidatorBuilder)->fromSchema($schema);
    }

    /**
     * @param ServerRequestInterface $request
     * @throws ValidationFailed
     */
    public function validateRequest(ServerRequestInterface $request): void
    {
        $validator = $this->validator->getRequestValidator();
        $validator->validate($request);
    }

    /**
     * @param string            $path
     * @param string            $method
     * @param ResponseInterface $response
     * @throws ValidationFailed
     */
    public function validateResponse(string $path, string $method, ResponseInterface $response): void
    {
        $validator = $this->validator->getResponseValidator();
        $address = new OperationAddress($path, $method);
        $validator->validate($address, $response);
    }
}
