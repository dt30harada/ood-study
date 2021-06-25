<?php

namespace Packages\ObjectOriented\Domain\Order\Detail;

use Packages\ObjectOriented\Domain\Item\Item;

/**
 * 注文明細 行
 */
final class OrderDetail
{
    private Item $item;
    private Quantity $quantity;

    public function __construct(Item $item, Quantity $quantity)
    {
        $this->item = $item;
        $this->quantity = $quantity;
    }

    public function getItemId(): int
    {
        return $this->item->getId();
    }

    public function getItemName(): string
    {
        return $this->item->getName();
    }

    public function getItemCategoryName(): string
    {
        return $this->item->getCategoryName();
    }

    public function getUnitPrice(): int
    {
        return $this->item->getUnitPrice()->getValue();
    }

    public function getUnitSalesTax(): int
    {
        return $this->item->getUnitSalesTax();
    }

    public function getQuantity(): Quantity
    {
        return $this->quantity;
    }

    public function getOrderPrice(): int
    {
        return $this->getUnitPrice() * $this->quantity->getValue();
    }

    public function getOrderSalesTax(): int
    {
        return $this->getUnitSalesTax() * $this->quantity->getValue();
    }
}
