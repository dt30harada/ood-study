<?php

namespace Database\Seeders\master;

use Database\Seeders\master\TruncatableTemplateSeeder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class ItemCategoriesSeeder extends TruncatableTemplateSeeder
{
    protected function newQueryBuilder(): Builder
    {
        return DB::table('item_categories');
    }

    protected function prepareInsertData(): array
    {
        return [
            ['id' => 1, 'label' => '野菜'],
            ['id' => 2, 'label' => '果物'],
            ['id' => 3, 'label' => '飲料'],
        ];
    }
}
