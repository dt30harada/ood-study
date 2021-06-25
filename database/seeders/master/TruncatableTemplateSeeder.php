<?php

namespace Database\Seeders\master;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

abstract class TruncatableTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('set foreign_key_checks=0');
        $query = $this->newQueryBuilder();
        $query->truncate();
        $query->insert($this->prepareInsertData());
        DB::statement('set foreign_key_checks=1');
    }

    /**
     * シーディング対象テーブルのクエリビルダを生成する
     *
     * @return Builder DB::table('concrete_table_name')
     */
    abstract protected function newQueryBuilder(): Builder;

    /**
     * 挿入するマスタデータを用意する
     *
     * @return array Builder::insert()の引数に渡す多次元配列
     */
    abstract protected function prepareInsertData(): array;
}
