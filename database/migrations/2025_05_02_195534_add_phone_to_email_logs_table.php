<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('recipients');
        });
    }
    
    public function down()
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn('phone');
        });
    }
};
