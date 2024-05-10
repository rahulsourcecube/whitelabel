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
        Schema::table('community', function (Blueprint $table) {
            $table->enum('status', ['0', '1'])->default('0')->comment('0 = Active, 1 = Inactive')->after('image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
