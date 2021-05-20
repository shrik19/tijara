<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaymentStatusToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('order_number')->after('basket_id')->nullable();
            $table->string('transaction_number')->after('order_number')->nullable();
            $table->string('payment_method')->after('commision_amount')->nullable();
            $table->string('payment_status')->after('payment_method')->default('Unpaid');
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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('order_number');
            $table->dropColumn('transaction_number');
            $table->dropColumn('payment_method');
            $table->dropColumn('payment_status');
            $table->dropColumn('payment_status_date');
        });
    }
}
