<?php

namespace Packages\ObjectOriented\Domain\Order;

use Packages\ObjectOriented\Domain\Customer\Customer;
use Packages\ObjectOriented\Domain\Order\Detail\OrderDetails;
use Packages\Procedural\Utility\SalesTax;

/**
 * 注文エンティティ
 *
 * @note entity:
 * - ドメイン(対象領域)の概念モデルをオブジェクトに反映したもの
 * - 属性ではなく識別子によりその同一性が判断される
 * - 可変オブジェクト(であることが多い)
 */
final class Order
{
    private OrderId $id;
    private OrderDatetime $datetime;
    private Customer $customer;
    private OrderDetails $orderDetails;

    // TODO 値オブジェクト化
    private int $subtotalPrice;
    private int $discount;
    private int $shipping;
    private int $totalPrice;

    public function __construct(
        OrderId $id,
        string $datetime,
        Customer $customer,
        OrderDetails $details
    ) {
        $this->id = $id;
        $this->datetime = new OrderDatetime($datetime);
        $this->customer = $customer;
        $this->orderDetails = $details;

        // 小計
        $this->calcSubtotalPrice();
        // 割引額
        $this->calcDiscount();
        // 送料
        $this->calcShipping();
        // 総計額
        $this->calcTotalPrice();
    }

    /**
     * 小計(税抜)を算出する
     *
     * @return void
     */
    private function calcSubtotalPrice(): void
    {
        $this->subtotalPrice = $this->orderDetails->calcSubtotalPrice();
    }

    /**
     * 割引額を算出する
     *
     * @return void
     */
    private function calcDiscount(): void
    {
        $customerRank = $this->customer->getRank();

        $this->discount = $customerRank->calcDiscount($this);
    }

    /**
     * 送料を算出する
     *
     * @return void
     */
    private function calcShipping(): void
    {
        $customerRank = $this->customer->getRank();

        $this->shipping = $customerRank->calcShipping($this);
    }

    /**
     * 総計額を算出する
     *
     * @return void
     */
    private function calcTotalPrice(): void
    {
        $shipping_without_tax = SalesTax::calcPriceWithoutTax($this->shipping, 0.1, 1);
        $discount_without_tax = SalesTax::calcPriceWithoutTax($this->discount, 0.1, 0);
        $this->totalPrice =
            $this->subtotalPrice - $discount_without_tax + $shipping_without_tax;
    }


    public function getOrderId(): OrderId
    {
        return $this->id;
    }

    public function getDatetime(): OrderDatetime
    {
        return $this->datetime;
    }

    public function getCustomerId(): int
    {
        return $this->customer->getId()->getValue();
    }

    public function getCustomerName(): string
    {
        return $this->customer->getName();
    }

    public function getOrderDetails(): array
    {
        return $this->orderDetails->getOrderDetails();
    }

    public function getSubtotalPrice(): int
    {
        return $this->subtotalPrice;
    }

    public function getSubtotalSalesTax(): int
    {
        return SalesTax::calc($this->subtotalPrice, 0.1);
    }

    public function getDiscount(): int
    {
        return $this->discount;
    }

    public function getShipping(): int
    {
        return $this->shipping;
    }

    public function getTotalPrice(): int
    {
        return $this->totalPrice;
    }

    public function getTotalSalesTax(): int
    {
        return SalesTax::calc($this->totalPrice, 0.1);
    }
}
