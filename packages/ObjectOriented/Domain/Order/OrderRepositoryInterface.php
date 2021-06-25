<?php

namespace Packages\ObjectOriented\Domain\Order;

/**
 * 注文関連モデルのリポジトリ
 *
 * - リポジトリを用意する単位
 * -- ルート集約ごとに用意する
 * -- ルート集約: 整合性が強く求められるドメインモデルのうち中心となるもの
 * -- 例：注文エンティティと注文明細エンティティなら注文エンティティ
 * - 依存関係逆転の原則(DIP)
 * -- 特定の技術的関心事に依存しないようインターフェイス化
 * -- 利用するドメイン側にリポジトリの所有権をもたせる
 * -- DIコンテナでこのインターフェイスと実装クラスをバインドすることを忘れずに
 * -- @see \App\Providers\RepositoryServiceProvider
 */
interface OrderRepositoryInterface
{
    /**
     * 指定の注文エンティティを保存する
     *
     * @param Order $order
     * @return void
     */
    public function save(Order $order): void;

    /**
     * 一意の注文IDを生成する
     *
     * @return OrderId
     */
    public function generateUniqueOrderId(): OrderId;
}
