<?php

namespace App\Http\Controllers\Api\Procedural;

use App\Http\Controllers\Api\BaseController;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Packages\Procedural\OrderProcessUseCase;

final class OrderController extends BaseController
{
    /**
     * POST 商品注文処理
     *
     * @param Request $request
     * @param OrderProcessUseCase $usecase
     * @return JsonResponse
     */
    public function order(Request $request, OrderProcessUseCase $usecase): JsonResponse
    {
        // 入力値バリデーション
        if ($message = $this->validateReqestParam($request)) {
            return $this->responseForValidationError($message);
        }

        try {
            // ユースケースを実行
            $order = $usecase->execute($request);

            // 注文情報をJSON形式で返却
            return $this->responseForAllGreen($this->formatForResponse($order));

        } catch (\Exception $e) {
            report($e);
            return $this->responseForSeverError($e->getMessage());
        }
    }

    /**
     * 入力値バリデーション
     *
     * @param Request $request
     * @return string
     */
    private function validateReqestParam(Request $request): string
    {
        $validator = Validator::make(
            $request->all(),
            [
                'order_datetime' => 'required|date_format:Y-m-d H:i:s',
                'customer_id' => 'required',
                'items.*.item_id' => 'required',
                'items.*.quantity' => 'required|numeric',
            ],
            [
                'order_datetime.required' => '注文日時必須',
                'order_datetime.date_format' => '注文日時の形式が違う',
                'customer_id.required' => '顧客ID必須',
                'items.*.item_id.required' => '商品ID必須',
                'items.*.quantity.required' => '個数必須',
                'items.*.quantity.numeric' => '個数は数値で',
            ]
        );

        return ($validator->fails())
            ? implode(',', $validator->errors()->all())
            : '';
    }

    /**
     * 注文情報をレスポンス用データに成形する
     *
     * @param Order $order
     * @return array
     */
    private function formatForResponse(Order $order): array
    {
        // 注文明細リスト
        $formatted_order_details = [];
        /** @var OrderDetail detail */
        foreach ($order->details as $detail) {
            $formatted_order_details[] = [
                'item_id' => $detail->item_id,
                'item_name' => $detail->item_name,
                'category_name' => $detail->item_category_name,
                'unit_price' => $detail->unit_price + $detail->unit_sales_tax,
                'unit_in_tax' => $detail->unit_sales_tax,
                'quantity' => $detail->quantity,
                'order_price' => $detail->order_price + $detail->order_sales_tax,
                'order_in_tax' => $detail->order_sales_tax,
            ];
        }

        return [
            'order' => [
                'order_id' => $order->id,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer_name,
                'datetime' => $order->order_datetime,
                'subtotal_price' => $order->subtotal_price + $order->subtotal_sales_tax,
                'subtotal_in_sales_tax' => $order->subtotal_sales_tax,
                'discount' => $order->discount,
                'shipping' => $order->shipping,
                'total_price' => $order->total_price + $order->total_sales_tax,
                'total_in_sales_tax' => $order->total_sales_tax,
                'order_details' => $formatted_order_details,
            ],
        ];
    }
}
