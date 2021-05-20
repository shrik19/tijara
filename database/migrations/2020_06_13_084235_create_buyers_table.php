<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBuyersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('address')->nullable(false);
            $table->string('province');
            $table->string('suburb');
            $table->string('account_holder')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_expiry')->nullable();
            $table->string('card_cvv')->nullable();
            $table->string('account_number')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('bank_branch')->nullable();
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
        Schema::dropIfExists('buyers');
    }
}
