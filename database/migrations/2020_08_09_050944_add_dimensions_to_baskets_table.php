<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionsToBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->longText('length')->after('basket_unit')->nullable(false);
            $table->longText('width')->after('length')->nullable(false);
            $table->longText('height')->after('width')->nullable(false);
            $table->longText('weight')->after('height')->nullable(false);
            $table->enum('show_on_home', ['0', '1'])->default('0')->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baskets', function (Blueprint $table) {
            $table->dropColumn('length');
            $table->dropColumn('width');
            $table->dropColumn('height');
            $table->dropColumn('weight');
            $table->dropColumn('show_on_home');
        });
    }
}
