<?php

namespace Database\Seeders\master;

use Database\Seeders\master\TruncatableTemplateSeeder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class ItemsSeeder extends TruncatableTemplateSeeder
{
    protected function newQueryBuilder(): Builder
    {
        return DB::table('items');
    }

    protected function prepareInsertData(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'トマト',
                'price' => 100,
                'category_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'りんご',
                'price' => 200,
                'category_id' => 2,
            ],
            [
                'id' => 3,
                'name' => 'ルイボスティー',
                'price' => 300,
                'category_id' => 3,
            ],
        ];
    }
}
