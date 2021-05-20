<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('billing_address')->nullable();
            $table->string('billing_street')->nullable();
            $table->string('billing_province')->nullable();
            $table->string('billing_suburb')->nullable();
            $table->string('billing_postcode')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('shipping_street')->nullable();
            $table->string('shipping_province')->nullable();
            $table->string('shipping_suburb')->nullable();
            $table->string('shipping_postcode')->nullable();
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
        Schema::dropIfExists('users_address');
    }
}
