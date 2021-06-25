<?php

namespace Packages\ObjectOriented\Domain\Order;

use InvalidArgumentException;

/**
 * 送料 値オブジェクト
 */
final class Shipping
{
    private int $value;

    /**
     * @param int $value
     * @throws InvalidArgumentException
     */
    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('shipping must be unsigned int');
        }
        $this->value = $value;
    }
}
