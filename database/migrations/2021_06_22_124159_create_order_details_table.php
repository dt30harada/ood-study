<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrderDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id')->unsigned();
            $table->integer('item_id')->unsigned()->comment('注文時の商品ID');
            $table->string('item_name')->comment('注文時の商品名');
            $table->string('item_category_name')->comment('注文時の商品カテゴリ名');
            $table->integer('unit_price')->unsigned()->comment('単価 税抜');
            $table->integer('unit_sales_tax')->unsigned()->comment('単価 消費税');
            $table->integer('quantity')->unsigned()->comment('数量');
            $table->integer('order_price')->unsigned()->comment('注文金額 税抜');
            $table->integer('order_sales_tax')->unsigned()->comment('注文金額 消費税');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));

            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_details');
    }
}
