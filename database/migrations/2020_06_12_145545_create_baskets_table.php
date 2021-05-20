<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title')->nullable(false);
            $table->enum('basket_type', ['Collection only', 'Multiple address', 'Delivery only'])->nullable();
            $table->float('price', 8, 2)->default(0.00);
            $table->integer('percent_to_sale')->default(0);
            $table->integer('basket_unit')->default(0);
            $table->integer('basket_quantity')->default(0);
            $table->integer('basket_discount')->default(0);
            $table->dateTime('basket_expiry');
            $table->enum('status', ['active', 'block'])->default('active');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['user_id']); // drop the foreign key.
        Schema::dropIfExists('baskets');
    }
}
