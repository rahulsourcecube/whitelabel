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
      
            Schema::table('user_campaign_history', function (Blueprint $table) {
                // Drop the 'verified_by' column
                $table->dropForeign(['verified_by']);
                $table->dropColumn('verified_by');
                $table->dropColumn('status');
                
                // Drop the foreign key reference for 'campaign_id'
                $table->dropForeign(['campaign_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_campaign_history', function (Blueprint $table) {
            // Recreate the 'verified_by' column
            $table->unsignedBigInteger('verified_by')->default('0');
            
            // Recreate the foreign key reference for 'campaign_id'
            $table->foreign('verified_by')->references('id')->on('company');
        });
            //
      
    }
};
