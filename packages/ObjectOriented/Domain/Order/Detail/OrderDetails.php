<?php

namespace Packages\ObjectOriented\Domain\Order\Detail;

/**
 * 注文明細リスト
 *
 * - ファーストクラスコレクション (コレクションオブジェクト)
 * -- コレクションのデータ構造とその操作をカプセル化する
 */
final class OrderDetails
{
    /** @var OrderDetail[] */
    private array $orderDetails;

    public function __construct(array $orderDetails)
    {
        foreach ($orderDetails as $detail){
            if (! $detail instanceof OrderDetail) {
                throw new \Exception('invalid type element exists');
            }
        }
        $this->orderDetails = $orderDetails;
    }

    /**
     * 小計(税抜)を算出する
     *
     * @return int
     */
    public function calcSubtotalPrice(): int
    {
        $result = 0;
        foreach ($this->orderDetails as $orderDetail) {
            $result += $orderDetail->getOrderPrice();
        }
        return $result;

    }

    /**
     * 注文明細リストのコピーを取得する
     *
     * @note 外部から要素を変更されないようにするためコピーして返却
     * @return array
     */
    public function getOrderDetails(): array
    {
        $result = [];
        foreach ($this->orderDetails as $orderDetail) {
            $result[] = clone $orderDetail;
        }
        return $result;
    }
}
