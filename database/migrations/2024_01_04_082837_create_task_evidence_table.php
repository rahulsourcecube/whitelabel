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
        Schema::create('task_evidence', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id')->nullable();
            $table->string('user_id')->nullable();
            $table->string('company_id')->nullable();
            $table->string('message')->nullable();
            $table->string('document')->nullable();
            $table->string('campaign_id')->nullable();
            $table->string('user_campaign_history_id')->nullable();
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
        Schema::dropIfExists('task_evidence');
    }
};
