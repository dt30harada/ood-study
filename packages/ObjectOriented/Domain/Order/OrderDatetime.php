<?php

namespace Packages\ObjectOriented\Domain\Order;

use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Support\Carbon;

/**
 * 注文日時 値オブジェクト
 */
final class OrderDatetime
{
    private Carbon $value;

    /**
     * @param string $datetime
     * @throws InvalidFormatException
     */
    public function __construct(string $datetime)
    {
        $this->value = Carbon::parse($datetime);
    }

    /**
     * 注文日に「7がつく」かどうか
     *
     * @return bool
     */
    public function is7Day(): bool
    {
        return strpos($this->value->day, '7') !== false;
    }

    public function __toString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }
}
