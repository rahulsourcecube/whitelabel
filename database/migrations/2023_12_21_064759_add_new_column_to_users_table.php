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
       
        Schema::table('users', function (Blueprint $table) {
            $table->string('facebook_link')->nullable()->after('remember_token');
            $table->string('linkedin_link')->nullable()->after('facebook_link');
            $table->string('twitter_link')->nullable()->after('linkedin_link');
            $table->string('youtube_link')->nullable()->after('twitter_link');
            $table->string('ac_holder')->nullable()->after('youtube_link');
            $table->string('ifsc_code')->nullable()->after('ac_holder');
            $table->string('ac_no')->nullable()->after('ifsc_code');
           
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
            $table->dropColumn(['facebook_link', 'linkedin_link', 'twitter_link', 'youtube_link',]);
        });
    }
};
