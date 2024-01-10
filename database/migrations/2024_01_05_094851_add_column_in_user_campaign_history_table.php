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
        Schema::table('user_campaign_history', function (Blueprint $table) {
            $table->enum('status', ['1', '2', '3', '4', '5'])->default('1')->comment('1) Pending 2) Claim Request 3) Completed 4) Rejected 5) reopen')->after('referral_link');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_campaign_history', function (Blueprint $table) {
            //
        });
    }
};
