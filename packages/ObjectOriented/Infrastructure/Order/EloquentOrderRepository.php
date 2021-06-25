<?php

namespace Packages\ObjectOriented\Infrastructure\Order;

use App\Models\Order as EloquentOrder;
use App\Models\OrderDetail as EloquentOrderDetail;
use Illuminate\Support\Facades\DB;
use Packages\ObjectOriented\Domain\Order\Detail\OrderDetail;
use Packages\ObjectOriented\Domain\Order\Order;
use Packages\ObjectOriented\Domain\Order\OrderId;
use Packages\ObjectOriented\Domain\Order\OrderRepositoryInterface;

/**
 * Eloquentを使用してOrderRepositoryInterfaceを実装したリポジトリ
 */
final class EloquentOrderRepository implements OrderRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function save(Order $order): void
    {
        // 注文情報の保存
        EloquentOrder::create([
            'id' => $order->getOrderId()->getValue(),
            'order_datetime' => $order->getDatetime(),
            'customer_id' => $order->getCustomerId(),
            'customer_name' => $order->getCustomerName(),
            'subtotal_price' => $order->getSubtotalPrice(),
            'subtotal_sales_tax' => $order->getSubtotalSalesTax(),
            'discount' => $order->getDiscount(),
            'shipping' => $order->getShipping(),
            'total_price' => $order->getTotalPrice(),
            'total_sales_tax' => $order->getTotalSalesTax(),
        ]);

        // 注文明細の保存
        /** @var OrderDetail $detail */
        foreach ($order->getOrderDetails() as $detail) {
            EloquentOrderDetail::create([
                'order_id' => $order->getOrderId()->getValue(),
                'item_id' => $detail->getItemId(),
                'item_name' => $detail->getItemName(),
                'item_category_name' => $detail->getItemCategoryName(),
                'unit_price' => $detail->getUnitPrice(),
                'unit_sales_tax' => $detail->getUnitSalesTax(),
                'quantity' => $detail->getQuantity()->getValue(),
                'order_price' => $detail->getOrderPrice(),
                'order_sales_tax' => $detail->getOrderSalesTax(),
            ]);
        }
    }

    /**
     * @inheritDoc
     */
    public function generateUniqueOrderId(): OrderId
    {
        if (! DB::statement('UPDATE `order_seq` SET `id` = LAST_INSERT_ID(`id` + 1)')) {
            throw new \Exception('failed to generate order_id');
        }

        $seq_id = DB::select('SELECT LAST_INSERT_ID() AS `order_id`')[0]->order_id;
        return new OrderId($seq_id);
    }
}
