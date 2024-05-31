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
        Schema::table('community_likes', function (Blueprint $table) {
            $table->enum('type', ['0', '1', '2'])->default('0')->comment('0 = Defult 1= Like, 2= Unlike ')->after('company_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('community_likes', function (Blueprint $table) {
            $table->removeColumn('type');
        });
    }
};
