<?php

namespace Packages\ObjectOriented\Domain\Item;

/**
 * 商品エンティティのリポジトリ
 */
interface ItemRepositoryInterface
{
    /**
     * 指定商品IDリストに該当する商品エンティティのマップを取得する
     *
     * @param array $item_ids
     * @return Item[] [[商品ID => 商品エンティティ], ...]
     */
    public function findByIds(array $item_ids): array;
}
