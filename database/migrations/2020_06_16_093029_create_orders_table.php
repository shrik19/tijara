<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('vendor_id');
            $table->unsignedBigInteger('basket_id');
            $table->integer('quantity')->default(0);
            $table->float('unit_price', 8, 2)->default(0.00);
            $table->float('discount', 8, 2)->default(0.00);
            $table->float('sub_total', 8, 2)->default(0.00);
            $table->float('tax', 8, 2)->default(0.00);
            $table->float('shipping_amount', 8, 2)->default(0.00);
            $table->float('total_amount', 8, 2)->default(0.00);
            $table->integer('commision')->default(0);
            $table->float('commision_amount', 8, 2)->default(0.00);
            $table->string('order_status')->nullable();
            $table->timestamps();
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
