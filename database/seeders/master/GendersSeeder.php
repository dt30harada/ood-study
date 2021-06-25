<?php

namespace Database\Seeders\master;

use Database\Seeders\master\TruncatableTemplateSeeder;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

final class GendersSeeder extends TruncatableTemplateSeeder
{
    protected function newQueryBuilder(): Builder
    {
        return DB::table('genders');
    }

    protected function prepareInsertData(): array
    {
        return [
            ['code' => '0', 'label' => '未回答'],
            ['code' => '1', 'label' => '男性'],
            ['code' => '2', 'label' => '女性'],
            ['code' => '9', 'label' => 'その他'],
        ];
    }
}
