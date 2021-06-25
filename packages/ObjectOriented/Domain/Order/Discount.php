<?php

namespace Packages\ObjectOriented\Domain\Order;

use InvalidArgumentException;

/**
 * 割引額 値オブジェクト
 */
final class Discount
{
    private int $value;

    /**
     * @param int $value
     * @throws InvalidArgumentException
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('discount must be unsigned int');
        }
        $this->value = $value;
    }
}
