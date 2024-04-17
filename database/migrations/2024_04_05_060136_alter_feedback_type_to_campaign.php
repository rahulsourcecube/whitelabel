<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campaign', function (Blueprint $table) {
            $table->string('feedback_type')->nullable()->after('package_id');           
            $table->text('referral_url_segment')->nullable()->after('feedback_type');   
            $table->unsignedBigInteger('country_id')->after('referral_url_segment')->nullable();
            $table->unsignedBigInteger('state_id')->after('country_id')->nullable();
            $table->unsignedBigInteger('city_id')->after('state_id')->nullable();        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campaign', function (Blueprint $table) {
            //
        });
    }
};
