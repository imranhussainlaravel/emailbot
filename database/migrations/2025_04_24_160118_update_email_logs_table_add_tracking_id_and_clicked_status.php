<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->string('tracking_id')->nullable()->after('status');
        });

        // Modify ENUM column - requires raw SQL
        DB::statement("ALTER TABLE email_logs MODIFY status ENUM('sent', 'opened', 'bounced', 'clicked')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('email_logs', function (Blueprint $table) {
            $table->dropColumn('tracking_id');
        });

        // Revert ENUM column
        DB::statement("ALTER TABLE email_logs MODIFY status ENUM('sent', 'opened', 'bounced')");
    }
};
