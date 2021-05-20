<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('name');
            $table->string('mobile_number')->nullable()->after('password');
            $table->date('date_of_birth')->nullable()->after('mobile_number');
            $table->enum('status', ['active', 'block'])->default('active')->after('date_of_birth');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('username');
            $table->dropColumn('mobile_number');
            $table->dropColumn('date_of_birth');
            $table->dropColumn('status');
        });
    }
}
