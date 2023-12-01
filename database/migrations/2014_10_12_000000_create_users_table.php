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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('view_password')->nullable();
            $table->string('contact_number')->nullable();
            $table->integer('company_id')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('referral_code')->nullable();
            $table->integer('referral_user_id')->nullable();
            $table->enum('status', ['0', '1'])->default('1')->comment(' 0) Active, 1) Deactive');
            $table->enum('user_type', ['1', '2', '3', '4'])->default('1')->comment(' 0) Admin, 1) Company 3) Staff 4) User');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
