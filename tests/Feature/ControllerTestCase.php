<?php

declare(strict_types=1);

namespace Tests\Feature;

use Exception;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Testing\TestResponse;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Tests\TestCase;
use Throwable;

class ControllerTestCase extends TestCase
{
    protected OpenApiValidator $validator;

    public function setUp(): void
    {
        parent::setUp();
        // MEMO: インスタンスの生成（validatorの生成）回数をなるべく減らしたい
        try {
            $this->validator = app()->make(OpenApiValidator::class);
        } catch (Throwable $e) {
            self::fail("OpenAPIドキュメントからValidatorを生成できませんでした\n" . $e->getMessage());
        }
    }

    /**
     * 既存のget + openapiでのバリデーションを行う.
     * @param string $uri
     * @param array  $headers
     * @param bool   $shouldValidateRequest
     * @return TestResponse
     */
    public function getAndValidate($uri, array $headers = [], bool $shouldValidateRequest = false): TestResponse
    {
        return $this->wrapSendRequest('GET', $uri, $headers, shouldValidateRequest: $shouldValidateRequest);
    }

    /**
     * 既存のpost + openapiでのバリデーションを行う.
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $shouldValidateRequest
     * @return TestResponse
     */
    public function postAndValidate($uri, array $data = [], array $headers = [], bool $shouldValidateRequest = false): TestResponse
    {
        return $this->wrapSendRequest('POST', $uri, $headers, $data, shouldValidateRequest: $shouldValidateRequest);
    }

    /**
     * 既存のput + openapiでのバリデーションを行う.
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $shouldValidateRequest
     * @return TestResponse
     */
    public function putAndValidate($uri, array $data = [], array $headers = [], bool $shouldValidateRequest = false): TestResponse
    {
        return $this->wrapSendRequest('PUT', $uri, $headers, $data, shouldValidateRequest: $shouldValidateRequest);
    }

    /**
     * 既存のpatch + openapiでのバリデーションを行う.
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $shouldValidateRequest
     * @return TestResponse
     */
    public function patchAndValidate($uri, array $data = [], array $headers = [], bool $shouldValidateRequest = false): TestResponse
    {
        return $this->wrapSendRequest('PATCH', $uri, $headers, $data, shouldValidateRequest: $shouldValidateRequest);
    }

    /**
     * 既存のdelete + openapiでのバリデーションを行う.
     * @param string $uri
     * @param array  $data
     * @param array  $headers
     * @param bool   $shouldValidateRequest
     * @return TestResponse
     */
    public function deleteAndValidate($uri, array $data = [], array $headers = [], bool $shouldValidateRequest = false): TestResponse
    {
        return $this->wrapSendRequest('DELETE', $uri, $headers, $data, shouldValidateRequest: $shouldValidateRequest);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param array  $headers
     * @param array  $body
     * @param bool   $shouldValidateRequest
     * @return TestResponse
     *
     * 基底メソッド
     * - Option: 引数からPSR-7のRequestを生成
     * - Option: OpenAPIドキュメント(openapi-psr7-validator) でRequestのバリデーション
     * - 標準のリクエストメソッドを呼び出す
     * - 返り値をPSR-7のResponseに変換
     * - OpenAPIドキュメント(openapi-psr7-validator) でResponseのバリデーション
     */
    protected function wrapSendRequest(string $method, string $uri, array $headers = [], array $body = [], bool $shouldValidateRequest = false): TestResponse
    {
        $method = strtolower($method);
        // 指定された場合にRequestのバリデーションを行う
        if ($shouldValidateRequest) {
            // バリデーション用リクエストの生成（ref: Illuminate\Foundation\Testing\Concerns\MakesHttpRequests->json）
            // MEMO: Content-Typeはこの文字列じゃないとopenapi-psr7-validatorが認識しない
            $psr7Request = new ServerRequest(
                $method,
                $uri,
                array_merge(['Content-Type' => 'application/json'], $headers),
                json_encode($body),
                $this->transformHeadersToServerVars($headers)
            );
            // リクエストのバリデーション
            $this->validateRequest($psr7Request);
        }
        // 標準メソッドの実行
        $response = parent::json(
            $method,
            $uri,
            $body,
            $headers
        );
        // バリデーション用レスポンスの生成
        $psr7Response = new Response(
            $response->getStatusCode(),
            $response->headers->all(),
            $response->getContent(),
        );
        // レスポンスのバリデーション
        $this->validateResponse($uri, $method, $psr7Response);
        return $response;
    }

    /**
     * Requestのバリデーションを実行する.
     * @param ServerRequestInterface $psr7Request
     */
    private function validateRequest(ServerRequestInterface $psr7Request): void
    {
        try {
            $this->validator->validateRequest($psr7Request);
        } catch (ValidationFailed $e) {
            $msg = $this->generateFailedMessage($e);
            $this->fail('Request: ' . $msg);
        }
    }

    /**
     * Responseのバリデーションを実行する.
     * @param string            $uri
     * @param string            $method
     * @param ResponseInterface $psr7Response
     */
    private function validateResponse(string $uri, string $method, ResponseInterface $psr7Response): void
    {
        try {
            $this->validator->validateResponse($uri, $method, $psr7Response);
        } catch (ValidationFailed $e) {
            $msg = $this->generateFailedMessage($e);
            $psr7Response->getBody()->rewind();
            $this->fail('Response: ' . $msg);
        }
    }

    /**
     * validationのエラーを再帰的に取得し、整形して返却する.
     * @param Exception $exception
     * @return string
     */
    private function generateFailedMessage(Exception $exception): string
    {
        do {
            $errors[] = $exception->getMessage();

            if ($exception instanceof SchemaMismatch) {
                $errors[] = 'dataBreadCrumb: ' . implode(', ', $exception->dataBreadCrumb()->buildChain());
            }
            $exception = $exception->getPrevious();
        } while ($exception !== null);
        return implode("\n\t\t", $errors);
    }
}
