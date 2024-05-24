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
        Schema::table('setting', function (Blueprint $table) {
            $table->string('sms_account_to_number')->nullable()->after('sms_account_number');
            $table->string('sms_mode')->nullable()->after('sms_account_to_number');
            // $table->renameColumn('sms_account_number', 'sms_account_from_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('setting', function (Blueprint $table) {
            // $table->dropColumn('sms_account_to_number');
            // $table->renameColumn('sms_account_from_number', 'sms_account_number');
        });
    }
};