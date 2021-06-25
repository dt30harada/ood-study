<?php

namespace Packages\ObjectOriented\Domain\Item;

use InvalidArgumentException;

/**
 * 商品単価 値オブジェクト
 */
final class UnitPrice
{
    private int $value;

    /**
     * @param int $value 0以上の整数
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('unit_price must be int and 0 or more');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
