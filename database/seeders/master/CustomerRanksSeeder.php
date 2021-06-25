<?php

namespace Database\Seeders\master;

use Database\Seeders\master\TruncatableTemplateSeeder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class CustomerRanksSeeder extends TruncatableTemplateSeeder
{
    protected function newQueryBuilder(): Builder
    {
        return DB::table('customer_ranks');
    }

    protected function prepareInsertData(): array
    {
        return [
            ['id' => 1, 'label' => '一般'],
            ['id' => 2, 'label' => 'VIP'],
        ];
    }
}
