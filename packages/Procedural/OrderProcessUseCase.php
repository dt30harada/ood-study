<?php

namespace Packages\Procedural;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Packages\Procedural\Utility\SalesTax;

/**
 * 顧客が商品を注文するユースケース
 */
final class OrderProcessUseCase
{
    /**
     * @param Request $request
     * @return Order
     * @throws \Exception
     */
    public function execute(Request $request): Order
    {
        return DB::transaction(function () use ($request) {
            // 注文情報を生成する
            $customer = Customer::find($request->customer_id);
            $items = $this->fetchItemsForOrderDetailSource($request->items);
            $order = $this->createOrder($customer, $request->order_datetime, $items);

            // 注文情報を保存する
            $this->saveOrder($order);

            // 決済処理を実行する
            $this->executePayment($order);

            DB::commit();

            // 注文情報を表示する
            return $order;
        });
    }

    /**
     * 注文明細の生成に必要な商品エンティティのリストを取得する
     *
     * @param array $item_id_qty_pairs
     * @return \stdClass[] 各商品エンティティは注文数量と商品カテゴリのプロパティを保有する
     */
    private function fetchItemsForOrderDetailSource(array $item_id_qty_pairs): array
    {
        $result = [];

        $items = $this->fetchItemMapByIds(array_column($item_id_qty_pairs, 'item_id'));

        foreach ($item_id_qty_pairs as $pair) {
            $item = $items[$pair['item_id']] ?? null;
            if (is_null($item)) {
                throw new \Exception('item not found');
            }
            $item->quantity = (int) $pair['quantity'];
            $result[] = $item;
        }

        return $result;
    }

    /**
     * 注文エンティティを生成する
     *
     * @param Customer $customer
     * @param string $order_datetime
     * @param \stdClass[] $items 注文数量とカテゴリ情報をもつ商品情報エンティティのリスト
     * @return Order 注文エンティティ(detailsプロパティで注文明細エンティティのリストを保有)
     */
    private function createOrder(Customer $customer, string $order_datetime, array $items): Order
    {
        $order = new Order();
        $order->id = $this->generateUniqueOrderId();
        $order->order_datetime = $order_datetime;
        $order->customer_id = $customer->id;
        $order->customer_name = $customer->name;

        // 注文明細エンティティのリストを生成する
        $order->details = $this->createOrderDetails($order->id, $items);

        // 小計
        $order->subtotal_price = $this->calcSubtotalPrice($order->details);
        $order->subtotal_sales_tax = SalesTax::calc($order->subtotal_price, 0.1);
        // 割引額
        $order->discount = $this->calcDiscount($order, $customer->customer_rank_id);
        // 送料
        $order->shipping = $this->calcShipping($order, $customer->customer_rank_id);
        // 総計
        $order->total_price = $this->calcTotalPrice($order);
        $order->total_sales_tax = SalesTax::calc($order->total_price, 0.1);

        return $order;
    }

    /**
     * 一意の注文IDを生成する
     *
     * @return int
     * @throws \Exception 生成に失敗したとき
     */
    private function generateUniqueOrderId(): int
    {
        if (DB::statement('UPDATE `order_seq` SET `id` = LAST_INSERT_ID(`id` + 1)')) {
            return DB::select('SELECT LAST_INSERT_ID() AS `order_id`')[0]->order_id;
        }
        throw new \Exception('failed to generate order_id');
    }

    /**
     * 注文明細エンティティのリストを生成する
     *
     * @param int $order_id
     * @param \stdClass[] $items
     * @return OrderDetail[]
     * @throws \Exception 存在しない商品IDが指定されていた場合
     */
    private function createOrderDetails(int $order_id, array $items): array
    {
        $result = [];

        foreach ($items as $item) {
            $sales_tax = SalesTax::calc($item->price, 0.1);

            $orderDetail = new OrderDetail([
                'order_id' => $order_id,
                'item_id' => $item->id,
                'item_name' => $item->name,
                'item_category_name' => $item->category_label,
                'unit_price' => $item->price,
                'unit_sales_tax' => $sales_tax,
                'quantity' => $item->quantity,
                'order_price' => $item->price * $item->quantity,
                'order_sales_tax' => $sales_tax * $item->quantity,
            ]);

            $result[] = $orderDetail;
        }

        return $result;
    }

    /**
     * 指定IDにマッチする商品情報のマップを取得する
     *
     * @param array $itemIds
     * @return array [商品ID => 商品情報(\stdClass), ...]
     */
    private function fetchItemMapByIds(array $itemIds): array
    {
        $result = DB::table('items as I')
            ->leftJoin('item_categories as IC', 'I.category_id', '=', 'IC.id')
            ->whereIn('I.id', $itemIds)
            ->select([
                'I.id',
                'I.name',
                'I.price',
                'IC.label as category_label',
            ])
            ->get()
            ->keyBy('id')
            ->toArray();

        return $result;
    }

    /**
     * 注文情報を保存する
     *
     * @param Order $order
     * @return void
     */
    private function saveOrder(Order $order): void
    {
        // NOTE 注文明細リストをもったままだと保存できないため一時退避
        $order_details = $order->details;
        unset($order->details);

        $order->save();
        $order->details = $order_details;

        // 注文明細リスト
        foreach ($order->details as $detail) {
            $detail->save();
        }
    }

    /**
     * 小計(税抜)を算出する
     *
     * @param OrderDetail[] $order_details
     * @return int
     */
    private function calcSubtotalPrice(array $order_details): int
    {
        $result = 0;
        foreach ($order_details as $detail) {
            $result += $detail->order_price;
        }
        return $result;
    }

    /**
     * 割引額を算出する
     *
     * - 顧客ランクが一般の場合: 7がつく日なら小計(税抜)の5%
     * - 顧客ランクがVIPの場合: 常に小計(税抜)の5%
     *
     * @param Order $order
     * @param int $customer_rank_id
     * @return int
     * @throws \Exception 顧客ランクが未知の場合
     */
    private function calcDiscount(Order $order, int $customer_rank_id): int
    {
        switch ($customer_rank_id) {
            case 1: // 一般
                $datetime = Carbon::parse($order->order_datetime);
                return (strpos($datetime->day, '7') !== false)
                    ? (int) bcmul($order->subtotal_price, 0.05, 0)
                    : 0;
            case 2: // VIP
                return (int) bcmul($order->subtotal_price, 0.05, 0);
            default:
                throw new \Exception('unknown customer_rank');
        }
    }

    /**
     * 送料を算出する
     *
     * - 顧客ランクが一般の場合: 一律500円。ただし小計(税抜)が5,000円以上なら無料
     * - 顧客ランクがVIPの場合: 常に無料
     *
     * @param Order $order
     * @param int $customer_rank_id
     * @return int
     * @throws \Exception 顧客ランクが未知の場合
     */
    private function calcShipping(Order $order, int $customer_rank_id): int
    {
        switch ($customer_rank_id) {
            case 1: // 一般
                return ($order->subtotal_price >= 5000) ? 0 : 500;
            case 2: // VIP
                return 0;
            default:
                throw new \Exception('unknown customer_rank');
        }
    }

    /**
     * 総計(税抜)を算出する
     *
     * @param Order $order
     * @return int
     */
    private function calcTotalPrice(Order $order): int
    {
        $shipping_without_tax = SalesTax::calcPriceWithoutTax($order->shipping, 0.1, 1);
        $discount_without_tax = SalesTax::calcPriceWithoutTax($order->discount, 0.1, 0);
        $result = $order->subtotal_price - $discount_without_tax + $shipping_without_tax;

        return $result;
    }

    /**
     * 決済処理を実行する
     *
     * @param Order $order
     * @return void
     * @throws \Exception 決済処理に失敗した場合
     */
    private function executePayment(Order $order): void
    {
        // TODO 外部の決済APIを実行する
        // throw new \Exception('failed to execute payment api');
    }
}
