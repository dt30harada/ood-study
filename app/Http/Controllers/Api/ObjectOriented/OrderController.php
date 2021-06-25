<?php

namespace App\Http\Controllers\Api\ObjectOriented;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Packages\ObjectOriented\Domain\Order\Detail\OrderDetail;
use Packages\ObjectOriented\Domain\Order\Order;
use Packages\ObjectOriented\UseCase\OrderProcessUseCase;

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
        foreach ($order->getOrderDetails() as $detail) {
            $formatted_order_details[] = [
                'item_id' => $detail->getItemId(),
                'item_name' => $detail->getItemName(),
                'category_name' => $detail->getItemCategoryName(),
                'unit_price' => $detail->getUnitPrice() + $detail->getUnitSalesTax(),
                'unit_in_tax' => $detail->getUnitSalesTax(),
                'quantity' => $detail->getQuantity()->getValue(),
                'order_price' => $detail->getOrderPrice() + $detail->getOrderSalesTax(),
                'order_in_tax' => $detail->getOrderSalesTax(),
            ];
        }

        return [
            'order' => [
                'order_id' => $order->getOrderId()->getValue(),
                'customer_id' => $order->getCustomerId(),
                'customer_name' => $order->getCustomerName(),
                'datetime' => (string) $order->getDatetime(),
                'subtotal_price' => $order->getSubtotalPrice() + $order->getSubtotalSalesTax(),
                'subtotal_in_sales_tax' => $order->getSubtotalSalesTax(),
                'discount' => $order->getDiscount(),
                'shipping' => $order->getShipping(),
                'total_price' => $order->getTotalPrice() + $order->getTotalSalesTax(),
                'total_in_sales_tax' => $order->getTotalSalesTax(),
                'order_details' => $formatted_order_details,
            ],
        ];
    }
}
