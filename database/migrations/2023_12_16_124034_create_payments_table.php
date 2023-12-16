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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('company_package_id')->nullable()->constrained('company_package');
            $table->string('amount')->nullable();
            $table->string('name_on_card')->nullable();
            $table->string('card_number')->nullable();
            $table->string('card_expiry_month')->nullable();
            $table->string('card_expiry_year')->nullable();
            $table->string('card_cvv')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('status')->default('success');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
};
