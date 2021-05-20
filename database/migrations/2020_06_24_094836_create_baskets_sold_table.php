<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsSoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets_sold', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('basket_id');
            $table->unsignedBigInteger('vendor_id');
            $table->float('sold_percent', 8, 2)->default(0.00);
            $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
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
        $table->dropForeign(['basket_id','vendor_id']); // drop the foreign key.
        Schema::dropIfExists('baskets_sold');
    }
}
