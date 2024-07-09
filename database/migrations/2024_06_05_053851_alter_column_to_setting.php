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
            $table->string('plivo_auth_id')->nullable()->after('sms_account_token');
            $table->string('plivo_auth_token')->nullable()->after('plivo_auth_id');
            $table->string('plivo_phone_number')->nullable()->after('plivo_auth_token');
            $table->string('plivo_test_phone_number')->nullable()->after('plivo_phone_number');
            $table->enum('plivo_mode', ['1', '2'])->default('1')->comment('1 =Test, 2 =live')->after('plivo_test_phone_number');
            $table->enum('sms_type', ['1', '2'])->default('1')->comment('1 =Twilivo, 2 =Plivo')->after('plivo_mode');
            $table->enum('community_status', ['1', '2'])->default('1')->comment('1=On, 2 = Off')->after('sms_type');
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
            $table->removeColumn('plivo_phone_number');
            $table->removeColumn('plivo_auth_token');
            $table->removeColumn('plivo_auth_id');
            $table->removeColumn('plivo_test_phone_number');
            $table->removeColumn('sms_type');
            $table->removeColumn('plivo_mode');
        });
    }
};
