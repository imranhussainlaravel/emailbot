<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->integer('opened_times')->default(0)->after('status'); // replace 'your_existing_column' with the column after which you want it
            $table->string('agent_name')->nullable()->after('opened_times');
        });
    }

    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn(['opened_times', 'agent_name']);
        });
    }
};
