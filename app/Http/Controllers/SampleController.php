<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Collective\Annotations\Routing\Annotations\Annotations\Controller;
use Collective\Annotations\Routing\Annotations\Annotations\Get;
use Vyuldashev\LaravelOpenApi\Attributes as OpenApi;
use App\Http\Controllers\Controller as BaseController;
use App\OpenApi\Parameters\SampleParameters;
use App\OpenApi\RequestBodies\AllOfSampleRequestBody;
use App\OpenApi\RequestBodies\OneOfSampleRequestBody;
use App\OpenApi\Responses\Errors\BadRequestResponse;
use App\OpenApi\Responses\Errors\InternalServerErrorResponse;
use App\OpenApi\Responses\Errors\NotFoundResponse;
use App\OpenApi\Responses\Errors\UnauthorizedResponse;
use App\OpenApi\Responses\SampleAssertResponse;
use App\OpenApi\Responses\SampleResponse;

/**
 * @Controller(prefix="/sample")
 */
#[OpenApi\PathItem]
class SampleController extends BaseController
{
    /**
     * title
     *
     * description
     *
     * @Get("/")
     * @return Response
     */
    #[OpenApi\Operation()]
    #[OpenApi\Response(SampleResponse::class, 200)]
    #[OpenApi\Response(BadRequestResponse::class, 400)]
    #[OpenApi\Response(UnauthorizedResponse::class, 401)]
    #[OpenApi\Response(NotFoundResponse::class, 404)]
    #[OpenApi\Response(InternalServerErrorResponse::class, 500)]
    public function index(): Response
    {
        return response('ok');
    }

    /**
     * OneOf
     *
     * OneOfの実装サンプル
     *
     * @Get("/oneof")
     * @return Response
     */
    #[OpenApi\Operation()]
    #[OpenApi\RequestBody(OneOfSampleRequestBody::class)]
    public function oneOf()
    {
    }

    /**
     * AllOf
     *
     * AllOfの実装サンプル
     *
     * @Get("/allof")
     * @return Response
     */
    #[OpenApi\Operation()]
    #[OpenApi\RequestBody(AllOfSampleRequestBody::class)]
    public function allOf()
    {
    }

    /**
     * クエリパラメータ
     *
     * クエリパラメータの実装サンプル
     *
     * @Get("/query_parameter")
     * @return Response
     */
    #[OpenApi\Operation()]
    #[OpenApi\Parameters(SampleParameters::class)]
    public function queryParameter()
    {
    }

    /**
     * パスパラメータ
     *
     * パスパラメータの実装サンプル
     *
     * @Get("/path_parameter/{id}")
     * @return Response
     */
    #[OpenApi\Operation()]
    public function pathParameter()
    {
    }

    /**
     * Assert
     *
     * レスポンスのアサーションテストサンプル
     *
     * @Get("/assertion")
     * @return void
     */
    #[OpenApi\Operation()]
    #[OpenApi\Response(SampleAssertResponse::class, 200)]
    public function sampleAssert()
    {
        return response()->json([
            'name' => '太郎',
            'age' => 20,
            'birthday' => '1999-04-25',
        ]);
    }
}
