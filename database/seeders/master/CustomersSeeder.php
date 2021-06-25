<?php

namespace Database\Seeders\master;

use Database\Seeders\master\TruncatableTemplateSeeder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class CustomersSeeder extends TruncatableTemplateSeeder
{
    protected function newQueryBuilder(): Builder
    {
        return DB::table('customers');
    }

    protected function prepareInsertData(): array
    {
        return [
            [
                'id' => 1,
                'name' => 'マイケル',
                'age' => 65,
                'customer_rank_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'ジョン',
                'age' => 30,
                'customer_rank_id' => 2,
            ],
        ];
    }
}
