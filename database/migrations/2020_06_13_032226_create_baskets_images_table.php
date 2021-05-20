<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('basket_id');
            $table->string('image')->nullable(false);
            $table->integer('image_order')->default(0);
            $table->enum('status', ['active', 'block'])->default('active');
            $table->timestamps();
            $table->foreign('basket_id')->references('id')->on('baskets')->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropForeign(['basket_id']); // drop the foreign key.
        Schema::dropIfExists('baskets_images');
    }
}
