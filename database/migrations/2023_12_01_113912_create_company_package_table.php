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
        Schema::create('company_package', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->nullable();
            $table->foreignId('package_id')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('no_of_campaign')->nullable();
            $table->float('price')->nullable();
            $table->string('paymnet_method')->nullable();
            $table->string('paymnet_id')->nullable();
            $table->text('paymnet_response')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment('1) Active  0) Inactive');
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('company');
            $table->foreign('package_id')->references('id')->on('package');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_package');
    }
};
