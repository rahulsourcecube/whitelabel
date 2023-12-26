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
        Schema::create('package', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->integer('no_of_campaign');
            $table->string('image')->nullable();
            $table->integer('duration');
            $table->float('price');
            $table->enum('type', ['1', '2' ,'3'])->default('1')->comment('1) Free 2) Monthly 3) Yearly');
            $table->enum('status', ['0', '1'])->default('1')->comment('1) Active  0) Inactive');
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package');
    }
};
