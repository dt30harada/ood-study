<?php

namespace Packages\ObjectOriented\Domain\Customer;

use Packages\ObjectOriented\Domain\Order\Order;

/**
 * 区分オブジェクト 顧客ランク:VIP
 */
final class VipRank extends Rank
{
    /**
     * @inheritDoc
     */
    protected function validate(int $rank_id): bool
    {
        return $rank_id === static::VIP;
    }

    /**
     * @inheritDoc
     *
     * 割引額算出ルール (VIP用)
     * - 常に小計(税抜)の5%
     */
    public function calcDiscount(Order $order): int
    {
        return (int) bcmul($order->getSubtotalPrice(), 0.05, 0);
    }

    /**
     * @inheritDoc
     *
     * 送料算出ルール (一般用)
     * - 常に無料
     */
    public function calcShipping(Order $order): int
    {
        return 0;
    }
}
