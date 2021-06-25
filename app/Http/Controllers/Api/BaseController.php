<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;

abstract class BaseController
{
    /**
     * レスポンス生成 正常終了 200
     *
     * @param array $data
     * @param string $message
     * @return JsonResponse
     */
    protected function responseForAllGreen(array $data, string $message = ''): JsonResponse
    {
        $resp = $this->getResponseTemplate();
        $resp['data'] = $data;
        $resp['message'] = $message ?: '正常終了';
        $resp['result'] = true;

        return response()
            ->json($resp, JsonResponse::HTTP_OK, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * レスポンス生成 バリデーションエラー 400
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function responseForValidationError(string $message = ''): JsonResponse
    {
        $resp = $this->getResponseTemplate();
        $resp['message'] = $message ?: 'バリデーションエラー';

        return response()
            ->json($resp, JsonResponse::HTTP_BAD_REQUEST, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * レスポンス生成 サーバーエラー 500
     *
     * @param string $message
     * @return JsonResponse
     */
    protected function responseForSeverError(string $message = ''): JsonResponse
    {
        $resp = $this->getResponseTemplate();
        $resp['message'] = $message ?: 'サーバーエラー';

        return response()
            ->json($resp, JsonResponse::HTTP_INTERNAL_SERVER_ERROR, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * レスポンスデータのテンプレート
     *
     * @return array
     */
    protected function getResponseTemplate(): array
    {
        return [
            'data' => null,
            'message' => '',
            'result' => false,
        ];
    }
}
