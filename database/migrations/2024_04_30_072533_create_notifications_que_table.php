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
        Schema::create('notifications_que', function (Blueprint $table) {
            $table->id();
            $table->integer('campaign_id')->nullable();
            $table->integer('company_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->enum('status', ['0', '1', '2'])->default('0')->comment('0 = Pending, 1 = Sent, 2 = Failed');
            $table->enum('notifications_type', ['1', '2', '3'])->nullable()->comment('1 = Mail, 2 = SMS, 3 = Mail and SMS');
            $table->string('type')->nullable();
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
        Schema::dropIfExists('notifications_que');
    }
};
