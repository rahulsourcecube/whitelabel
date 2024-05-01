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
            $table->enum('notifications_type', ['1', '2', '3'])->nullable()->comment('1 = Mail, 2 = SMS, 3 = Mail and SMS')->after('city_id');
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
            $table->dropColumn('notifications_type');
        });
    }
};
