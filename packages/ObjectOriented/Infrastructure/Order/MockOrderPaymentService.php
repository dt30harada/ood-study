<?php

namespace Packages\ObjectOriented\Infrastructure\Order;

use Packages\ObjectOriented\Domain\Order\Order;
use Packages\ObjectOriented\Domain\Order\OrderPaymentServiceInterface;

/**
 * 注文の決済処理を担うサービス
 *
 * @note テスト用のモック
 */
final class MockOrderPaymentService implements OrderPaymentServiceInterface
{
    /**
     * @inheritDoc
     */
    public function execute(Order $order): void
    {
        // throw new \Exception('failed to execute payment api');
    }
}
