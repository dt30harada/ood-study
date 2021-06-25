<?php

namespace Packages\ObjectOriented\Infrastructure\Item;

use Illuminate\Support\Facades\DB;
use Packages\ObjectOriented\Domain\Item\Item;
use Packages\ObjectOriented\Domain\Item\ItemRepositoryInterface;

/**
 * 商品エンティティのリポジトリ
 */
final class EloquentItemRepository implements ItemRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function findByIds(array $item_ids): array
    {
        $rawItems = DB::table('items as I')
            ->leftJoin('item_categories as IC', 'I.category_id', '=', 'IC.id')
            ->whereIn('I.id', $item_ids)
            ->select([
                'I.id',
                'I.name',
                'I.price',
                'I.id as category_id',
                'IC.label as category_label',
            ])
            ->get()
            ->keyBy('id');

        $items = $rawItems->map(function ($rawItem) {
            return new Item(
                $rawItem->id,
                $rawItem->name,
                $rawItem->category_id,
                $rawItem->category_label,
                $rawItem->price
            );
        });

        return $items->toArray();
    }
}
