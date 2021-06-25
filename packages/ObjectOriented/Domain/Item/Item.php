<?php

namespace Packages\ObjectOriented\Domain\Item;

use InvalidArgumentException;
use Packages\Procedural\Utility\SalesTax;

/**
 * 商品エンティティ
 */
final class Item
{
    private int $id;
    private string $name;
    private Category $category;

    private UnitPrice $unitPrice;

    public function __construct(
        int $id,
        string $name,
        int $category_id,
        string $category_label,
        int $unit_price
    ) {
        if ($id < 1) {
            throw new InvalidArgumentException('invalid item_id');
        }
        if ($name === '' || mb_strlen($name) > 30) {
            throw new InvalidArgumentException('invalid item_name');
        }

        $this->id = $id;
        $this->name = $name;
        $this->category = new Category($category_id, $category_label);
        $this->unitPrice = new UnitPrice($unit_price);
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategoryId(): int
    {
        return $this->category->getId();
    }

    public function getCategoryName(): string
    {
        return $this->category->getName();
    }

    public function getUnitPrice(): UnitPrice
    {
        return $this->unitPrice;
    }

    public function getUnitSalesTax(): int
    {
        // TODO ユーティリティクラスの排除
        return SalesTax::calc($this->unitPrice->getValue(), 0.1);
    }
}
