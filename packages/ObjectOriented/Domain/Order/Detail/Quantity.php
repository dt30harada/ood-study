<?php

namespace Packages\ObjectOriented\Domain\Order\Detail;

use InvalidArgumentException;

/**
 * 数量 値オブジェクト
 */
final class Quantity
{
    private int $value;

    /**
     * @param int $value 0以上の整数
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('quantity must be int and 0 or more');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
