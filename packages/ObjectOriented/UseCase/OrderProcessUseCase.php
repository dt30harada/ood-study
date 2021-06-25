<?php

namespace Packages\ObjectOriented\UseCase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Packages\ObjectOriented\Domain\Customer\CustomerId;
use Packages\ObjectOriented\Domain\Customer\CustomerRepositoryInterface;
use Packages\ObjectOriented\Domain\Item\ItemRepositoryInterface;
use Packages\ObjectOriented\Domain\Order\Detail\OrderDetail;
use Packages\ObjectOriented\Domain\Order\Detail\OrderDetails;
use Packages\ObjectOriented\Domain\Order\Detail\Quantity;
use Packages\ObjectOriented\Domain\Order\Order;
use Packages\ObjectOriented\Domain\Order\OrderPaymentServiceInterface;
use Packages\ObjectOriented\Domain\Order\OrderRepositoryInterface;

/**
 * 顧客が商品を注文するユースケース
 */
final class OrderProcessUseCase
{
    /** @var CustomerRepositoryInterface */
    private $customerRepo;

    /** @var ItemRepositoryInterface */
    private $itemRepo;

    /** @var OrderRepositoryInterface */
    private $orderRepo;

    /** @var OrderPaymentServiceInterface */
    private $paymentService;

    public function __construct(
        CustomerRepositoryInterface $customerRepo,
        ItemRepositoryInterface $itemRepo,
        OrderRepositoryInterface $orderRepo,
        OrderPaymentServiceInterface $paymentService
    ) {
        $this->customerRepo = $customerRepo;
        $this->itemRepo = $itemRepo;
        $this->orderRepo = $orderRepo;
        $this->paymentService = $paymentService;
    }

    /**
     * ユースケースを実行
     *
     * @param Request $request
     * @return Order
     * @throws \Exception
     */
    public function execute(Request $request): Order
    {
        return DB::transaction(function () use ($request) {
            // 注文情報を生成する
            $order = $this->createOrder($request);

            // 注文情報を保存する
            $this->orderRepo->save($order);

            // 決済処理を実行する
            $this->paymentService->execute($order);

            // 注文情報を表示する
            return $order;
        });
    }

    /**
     * 注文エンティティを生成する
     *
     * @param Request $request
     * @return Order
     */
    private function createOrder(Request $request): Order
    {
        $customer = $this->customerRepo->findById(
            new CustomerId($request->customer_id)
        );

        $orderDetails = $this->createOrderDetails($request->items);

        $order = new Order(
            $this->orderRepo->generateUniqueOrderId(),
            $request->order_datetime,
            $customer,
            $orderDetails
        );

        return $order;
    }

    /**
     * 注文明細リストを生成する
     *
     * @param array $item_id_qty_pairs [[商品ID, 数量], ...]
     * @return OrderDetails
     * @throws \Exception 存在しない商品のIDが指定されていた場合
     */
    private function createOrderDetails(array $item_id_qty_pairs): OrderDetails
    {
        $items = $this->itemRepo->findByIds(array_column($item_id_qty_pairs, 'item_id'));

        $orderDetails = [];
        foreach ($item_id_qty_pairs as $pair) {
            $item = $items[$pair['item_id']] ?? null;
            if (is_null($item)) {
                throw new \Exception('item not found');
            }
            $orderDetails[] = new OrderDetail(
                $item,
                new Quantity((int) $pair['quantity'])
            );
        }

        return new OrderDetails($orderDetails);
    }
}
