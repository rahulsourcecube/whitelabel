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
        Schema::create('campaign', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->float('reward')->nullable();
            $table->string('image')->nullable();
            $table->date('expiry_date');
            $table->enum('type', ['1', '2' ,'3'])->default('1')->comment('1) Referral 2) Social 3) Custom');
            $table->enum('status', ['0', '1'])->default('1')->comment('1) Active  0) Inactive');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campaign');
    }
};
