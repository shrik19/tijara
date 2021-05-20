<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToBasketsSoldTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baskets_sold', function (Blueprint $table) {
            $table->string('payment_status')->after('sold_percent')->default('Unpaid');
            $table->dateTime('payment_status_date')->after('payment_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baskets_sold', function (Blueprint $table) {
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_status_date');
        });
    }
}
