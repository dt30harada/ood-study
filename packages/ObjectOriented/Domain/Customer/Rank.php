<?php

namespace Packages\ObjectOriented\Domain\Customer;

use InvalidArgumentException;
use Packages\ObjectOriented\Domain\Order\Order;

/**
 * 顧客ランク 区分オブジェクト
 */
abstract class Rank
{
    const REGULAR = 1;
    const VIP = 2;

    protected int $rank_id;

    public function __construct(int $rank_id)
    {
        if (! $this->validate($rank_id)) {
            throw new InvalidArgumentException('customer_rank_id is invalid');
        }
        $this->rank_id = $rank_id;
    }

    /**
     * 指定区分値のバリデーション
     *
     * @param int $rank_id
     * @return bool
     */
    abstract protected function validate(int $rank_id): bool;

    /**
     * 顧客ランクに対応する割引額を算出する
     *
     * @param Order $order
     * @return int
     */
    abstract public function calcDiscount(Order $order): int;

    /**
     * 顧客ランクに対応する送料を算出する
     *
     * @param Order $order
     * @return int
     */
    abstract public function calcShipping(Order $order): int;
}
