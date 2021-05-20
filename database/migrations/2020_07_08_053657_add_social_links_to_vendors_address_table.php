<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSocialLinksToVendorsAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendors_address', function (Blueprint $table) {
            $table->string('facebook_link')->after('shipping_postcode')->nullable();
            $table->string('instagram_link')->after('facebook_link')->nullable();
            $table->string('twitter_link')->after('instagram_link')->nullable();
            $table->string('website_link')->after('twitter_link')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendors_address', function (Blueprint $table) {
            $table->dropColumn('facebook_link');
            $table->dropColumn('instagram_link');
            $table->dropColumn('twitter_link');
            $table->dropColumn('website_link');
        });
    }
}
