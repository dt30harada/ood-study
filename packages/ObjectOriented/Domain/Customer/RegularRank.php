<?php

namespace Packages\ObjectOriented\Domain\Customer;

use Packages\ObjectOriented\Domain\Order\Order;

/**
 * 区分オブジェクト 顧客ランク:一般
 */
final class RegularRank extends Rank
{
    /**
     * @inheritDoc
     */
    protected function validate(int $rank_id): bool
    {
        return $rank_id === static::REGULAR;
    }

    /**
     * @inheritDoc
     *
     * 割引額算出ルール (一般用)
     * - 7がつく日なら小計(税抜)の5%
     */
    public function calcDiscount(Order $order): int
    {
        return ($order->getDatetime()->is7Day())
            ? (int) bcmul($order->getSubtotalPrice(), 0.05, 0)
            : 0;
    }

    /**
     * @inheritDoc
     *
     * 送料算出ルール (一般用)
     * - 一律500円
     * - ただし小計(税抜)が5,000円以上なら無料
     */
    public function calcShipping(Order $order): int
    {
        return ($order->getSubtotalPrice() >= 5000) ? 0 : 500;
    }
}
