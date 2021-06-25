<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->integer('customer_id')->unsigned()->comment('顧客ID');
            $table->string('customer_name', 30)->comment('顧客名');
            $table->dateTime('order_datetime')->comment('注文日時');
            $table->integer('subtotal_price')->unsigned()->comment('小計 税抜');
            $table->integer('subtotal_sales_tax')->unsigned()->comment('小計 消費税');
            $table->integer('discount')->unsigned()->comment('割引額');
            $table->integer('shipping')->unsigned()->comment('送料 税込');
            $table->integer('total_price')->unsigned()->comment('総計 税抜');
            $table->integer('total_sales_tax')->unsigned()->comment('総計 消費税');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
