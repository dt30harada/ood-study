<?php

namespace Packages\ObjectOriented\Domain\Customer;

use InvalidArgumentException;

/**
 * 顧客ID 値オブジェクト
 */
final class CustomerId
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new InvalidArgumentException('customer_id must be int and greater than 0');
        }
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
