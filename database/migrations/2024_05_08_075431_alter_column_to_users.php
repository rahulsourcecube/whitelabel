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
            $table->enum('mail_new_task_notification', ['0', '1'])->default('0')->comment('0 = Yes, 1 = No')->after('token');
            $table->enum('mail_custom_notification', ['0', '1'])->default('0')->comment('0 = Yes, 1 = No')->after('mail_new_task_notification');
            $table->enum('sms_new_task_notification', ['0', '1'])->default('0')->comment('0 = Yes, 1 = No')->after('mail_custom_notification');
            $table->enum('sms_custom_notification', ['0', '1'])->default('0')->comment('0 = Yes, 1 = No')->after('sms_new_task_notification');
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
            $table->dropColumn('mail_new_task_notification');
            $table->dropColumn('mail_custom_notification');
            $table->dropColumn('sms_new_task_notification');
            $table->dropColumn('sms_custom_notification');
        });
    }
};
