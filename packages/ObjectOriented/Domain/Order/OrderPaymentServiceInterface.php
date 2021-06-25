<?php

namespace Packages\ObjectOriented\Domain\Order;

/**
 * 注文の決済処理を担うインターフェイス
 *
 * - IFとして切り出すことで依存先の決済サービスを切り替え可能になる
 * -- テスト時はモック実装を利用する
 */
interface OrderPaymentServiceInterface
{
    /**
     * 指定の注文エンティティを保存する
     *
     * @param Order $order
     * @return void
     * @throws \Exception 決済処理に失敗した場合
     */
    public function execute(Order $order): void;
}
