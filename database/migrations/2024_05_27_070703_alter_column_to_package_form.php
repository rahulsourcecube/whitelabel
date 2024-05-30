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
        Schema::table('package', function (Blueprint $table) {
            $table->enum('survey_status', ['0', '1'])->default('0')->comment('1= Active, 0 = Inactive')->after('no_of_employee');
            $table->text('no_of_survey')->nullable()->after('survey_status');
            $table->enum('community_status', ['0', '1'])->default('0')->comment('1= Active, 0 = Inactive')->after('no_of_survey');
            $table->enum('mail_temp_status', ['0', '1'])->default('0')->comment('1= Active, 0 = Inactive')->after('community_status');
            $table->enum('sms_temp_status', ['0', '1'])->default('0')->comment('1= Active, 0 = Inactive')->after('mail_temp_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package', function (Blueprint $table) {
        });
    }
};
