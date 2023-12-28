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
            $table->softDeletes()->nullable();
        });
        Schema::table('company', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('company_package', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('modules', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('notifications', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('package', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('setting', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('user_campaign_history', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
        Schema::table('user_social_link', function (Blueprint $table) {
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('all', function (Blueprint $table) {
            //
        });
    }
};
