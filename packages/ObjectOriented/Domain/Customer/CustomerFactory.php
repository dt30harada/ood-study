<?php

namespace Packages\ObjectOriented\Domain\Customer;

/**
 * 顧客関連モデルのファクトリ
 */
final class CustomerFactory
{
    /**
     * 顧客エンティティを生成する
     *
     * @param int $id
     * @param string $name
     * @param int $rank_id
     * @return Customer
     */
    public static function reconstruct(int $id, string $name, int $rank_id): Customer
    {
        return new Customer(
            new CustomerId($id),
            $name,
            self::reconstructRank($rank_id)
        );
    }

    /**
     * 顧客ランク区分オブジェクトを生成する
     *
     * @param int $rank_id
     * @return Rank
     */
    public static function reconstructRank(int $rank_id): Rank
    {
        // 顧客ランクの条件分岐をここに一元化する
        switch ($rank_id) {
            case 1:
                return new RegularRank($rank_id);
            case 2:
                return new VipRank($rank_id);
            default:
                throw new \Exception('unknown customer_rank_id');
        }
    }
}
