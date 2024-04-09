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
        Schema::create('task_progression_user_history', function (Blueprint $table) {
            $table->id();
            $table->string('company_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('progression_id')->nullable();
            $table->string('no_of_task')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('task_progression_user_history');
    }
};
