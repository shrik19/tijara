<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors_address', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_id')->default(0);
            $table->string('contact_firstname')->nullable();
            $table->string('contact_lastname')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_mobile')->nullable();
            $table->string('contact_landline')->nullable();
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
        Schema::dropIfExists('vendors_address');
    }
}
